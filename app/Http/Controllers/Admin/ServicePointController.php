<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePoint;
use App\Models\Client; // Required for listing clients in create/edit forms
use Illuminate\Http\Request;

class ServicePointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
use App\Models\Contract; // Adicionado

class ServicePointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contractId = $request->get('contract_id');
        $clientId = $request->get('client_id_filter'); // Para filtrar contratos por cliente

        $query = ServicePoint::with('contract.client')->latest();

        if ($contractId) {
            $query->where('contract_id', $contractId);
        } elseif ($clientId) {
            $query->whereHas('contract', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $servicePoints = $query->paginate(10);
        $clients = Client::orderBy('name')->get(); // Para filtro de cliente
        $contracts = Contract::with('client')->orderBy('name')->get(); // Para filtro de contrato direto

        return view('admin.service_points.index', compact('servicePoints', 'clients', 'contracts', 'contractId', 'clientId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $contracts = Contract::with('client')->orderBy('name')->get();
        if ($contracts->isEmpty()) {
            // Idealmente, deveria verificar se existem clientes e depois contratos
            return redirect()->route('admin.clients.index')->with('warning', 'Por favor, crie um cliente e um contrato antes de adicionar um posto de serviço.');
        }
        // Permitir pré-selecionar contrato se vier da página do contrato ou cliente
        $selectedContractId = $request->get('contract_id');
        return view('admin.service_points.create', compact('contracts', 'selectedContractId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contract_id' => 'required|exists:contracts,id',
            'address' => 'nullable|string',
            'latitude' => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'internal_code' => 'nullable|string|max:100|unique:service_points,internal_code',
        ]);

        ServicePoint::create($request->all());

        return redirect()->route('admin.service_points.index')
                         ->with('success', 'Posto de Serviço criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicePoint $servicePoint)
    {
        $servicePoint->load('contract.client');
        return view('admin.service_points.show', compact('servicePoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicePoint $servicePoint)
    {
        $contracts = Contract::with('client')->orderBy('name')->get();
        $servicePoint->load('contract.client');
        return view('admin.service_points.edit', compact('servicePoint', 'contracts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServicePoint $servicePoint)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contract_id' => 'required|exists:contracts,id',
            'address' => 'nullable|string',
            'latitude' => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'internal_code' => 'nullable|string|max:100|unique:service_points,internal_code,' . $servicePoint->id,
        ]);

        $servicePoint->update($request->all());

        return redirect()->route('admin.service_points.index')
                         ->with('success', 'Posto de Serviço atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicePoint $servicePoint)
    {
        // Consider what happens to users, occurrences associated.
        // For now, a simple delete. SoftDeletes is enabled.
        if ($servicePoint->users()->where('type', 'app_user')->count() > 0) {
            return redirect()->route('admin.service_points.index')
                             ->with('error', 'Posto de Serviço não pode ser excluído pois possui usuários de aplicativo associados.');
        }
        if ($servicePoint->occurrences()->count() > 0) {
            return redirect()->route('admin.service_points.index')
                             ->with('error', 'Posto de Serviço não pode ser excluído pois possui ocorrências associadas.');
        }

        $servicePoint->delete();

        return redirect()->route('admin.service_points.index')
                         ->with('success', 'Posto de Serviço excluído com sucesso.');
    }
}
