<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Client;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::with('client')->latest();

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->paginate(10);
        $clients = Client::orderBy('name')->get(); // For filter dropdown

        return view('admin.contracts.index', compact('contracts', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        if ($clients->isEmpty()) {
            return redirect()->route('admin.clients.create')->with('warning', 'Por favor, crie um cliente antes de adicionar um contrato.');
        }
        return view('admin.contracts.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'contract_code' => 'required|string|max:100|unique:contracts,contract_code',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,pending,expired',
            'details' => 'nullable|string',
        ]);

        Contract::create($validatedData);

        return redirect()->route('admin.contracts.index')
                         ->with('success', 'Contrato criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load(['client', 'servicePoints']);
        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $clients = Client::orderBy('name')->get();
        $contract->load('client');
        return view('admin.contracts.edit', compact('contract', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'contract_code' => 'required|string|max:100|unique:contracts,contract_code,' . $contract->id,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,pending,expired',
            'details' => 'nullable|string',
        ]);

        $contract->update($validatedData);

        return redirect()->route('admin.contracts.index')
                         ->with('success', 'Contrato atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if ($contract->servicePoints()->count() > 0) {
            return redirect()->route('admin.contracts.index')
                             ->with('error', 'Contrato não pode ser excluído pois possui postos de serviço associados.');
        }
        // Add other dependency checks if necessary (e.g., active occurrences linked to its service points)

        $contract->delete(); // Soft delete

        return redirect()->route('admin.contracts.index')
                         ->with('success', 'Contrato excluído com sucesso.');
    }
}
