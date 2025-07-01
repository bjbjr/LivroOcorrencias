<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255|unique:clients,contact_email', // Consider client specific uniqueness if necessary
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048' // Basic logo validation
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('client_logos', 'public'); // Changed folder to client_logos
            $validatedData['logo_path'] = $path;
        }
        unset($validatedData['logo']); // Unset original logo from validated data if path is set

        Client::create($validatedData);

        return redirect()->route('admin.clients.index')
                         ->with('success', 'Cliente criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load('servicePoints');
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:clients,name,' . $client->id,
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255|unique:clients,contact_email,' . $client->id,
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($client->logo_path) {
                Storage::disk('public')->delete($client->logo_path);
            }
            $path = $request->file('logo')->store('client_logos', 'public'); // Changed folder to client_logos
            $validatedData['logo_path'] = $path;
        }
        unset($validatedData['logo']); // Unset original logo from validated data if path is set or not

        $client->update($validatedData);

        return redirect()->route('admin.clients.index')
                         ->with('success', 'Cliente atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->servicePoints()->count() > 0) {
            return redirect()->route('admin.clients.index')
                             ->with('error', 'Cliente não pode ser excluído pois possui postos de serviço associados.');
        }

        if ($client->users()->where('type', 'app_user')->count() > 0) {
            return redirect()->route('admin.clients.index')
                             ->with('error', 'Cliente não pode ser excluído pois possui usuários de aplicativo associados.');
        }

        // Delete logo if it exists
        if ($client->logo_path) {
            Storage::disk('public')->delete($client->logo_path);
        }

        $client->delete(); // Soft delete

        return redirect()->route('admin.clients.index')
                         ->with('success', 'Cliente excluído com sucesso.');
    }
}
