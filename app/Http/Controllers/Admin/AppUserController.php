<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\ServicePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AppUserController extends Controller
{
    /**
     * Display a listing of the app users.
     */
    public function index(Request $request)
    {
        $query = User::where('type', 'app_user')->with(['client', 'servicePoint'])->latest();

        // Filtering logic (optional, can be expanded)
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $appUsers = $query->paginate(15);
        $clients = Client::orderBy('name')->get(); // For filter dropdown

        return view('admin.app_users.index', compact('appUsers', 'clients'));
    }

    /**
     * Show the form for editing the specified app user.
     * Also used for approving/assigning client/service point.
     */
    public function edit(User $appUser)
    {
        if ($appUser->type !== 'app_user') {
            return redirect()->route('admin.app_users.index')->with('error', 'Usuário inválido.');
        }
        $clients = Client::orderBy('name')->get();
        $servicePoints = $appUser->client_id ? ServicePoint::where('client_id', $appUser->client_id)->orderBy('name')->get() : collect();

        return view('admin.app_users.edit', compact('appUser', 'clients', 'servicePoints'));
    }

    /**
     * Update the specified app user in storage.
     */
    public function update(Request $request, User $appUser)
    {
        if ($appUser->type !== 'app_user') {
            return redirect()->route('admin.app_users.index')->with('error', 'Usuário inválido.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $appUser->id,
            'client_id' => 'nullable|exists:clients,id',
            'service_point_id' => 'nullable|exists:service_points,id',
            'approval_status' => 'required|in:pending,approved,rejected',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = $request->only(['name', 'email', 'client_id', 'service_point_id', 'approval_status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // If client_id is not provided or changed, service_point_id should be null
        if (is_null($request->client_id) || (isset($data['client_id']) && $data['client_id'] != $appUser->client_id)) {
            $data['service_point_id'] = null;
        }

        // If status is approved, ensure email_verified_at is set (if not already)
        if ($data['approval_status'] === 'approved' && is_null($appUser->email_verified_at)) {
            $data['email_verified_at'] = now();
        }


        $appUser->update($data);

        return redirect()->route('admin.app_users.index')->with('success', 'Usuário do aplicativo atualizado com sucesso.');
    }

    /**
     * Show the form for creating a new app user (by admin).
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $servicePoints = collect(); // Initially empty, populated by JS or after client selection
        return view('admin.app_users.create', compact('clients', 'servicePoints'));
    }

    /**
     * Store a newly created app user in storage (by admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'client_id' => 'nullable|exists:clients,id',
            'service_point_id' => 'nullable|exists:service_points,id',
            'approval_status' => 'required|in:pending,approved,rejected',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = $request->only(['name', 'email', 'client_id', 'service_point_id', 'approval_status']);
        $data['password'] = Hash::make($request->password);
        $data['type'] = 'app_user'; // Explicitly set type

        if ($data['approval_status'] === 'approved') {
            $data['email_verified_at'] = now();
        }

        // If client_id is not provided, service_point_id should be null
        if (is_null($request->client_id)) {
            $data['service_point_id'] = null;
        }

        User::create($data);

        return redirect()->route('admin.app_users.index')->with('success', 'Usuário do aplicativo criado com sucesso.');
    }


    /**
     * Remove the specified app user from storage.
     */
    public function destroy(User $appUser)
    {
        if ($appUser->type !== 'app_user') {
            return redirect()->route('admin.app_users.index')->with('error', 'Usuário inválido.');
        }

        // Add checks for dependencies like occurrences before deleting
        if ($appUser->occurrences()->count() > 0) {
             return redirect()->route('admin.app_users.index')->with('error', 'Usuário não pode ser excluído pois possui ocorrências registradas.');
        }

        $appUser->delete(); // Soft delete

        return redirect()->route('admin.app_users.index')->with('success', 'Usuário do aplicativo excluído com sucesso.');
    }

    /**
     * API endpoint to get service points for a client.
     * Used for dynamically populating dropdown in forms.
     */
    public function getServicePointsForClient(Request $request)
    {
        $request->validate(['client_id' => 'required|exists:clients,id']);
        $servicePoints = ServicePoint::where('client_id', $request->client_id)
                                     ->orderBy('name')
                                     ->get(['id', 'name']);
        return response()->json($servicePoints);
    }
}
