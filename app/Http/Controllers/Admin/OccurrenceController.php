<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occurrence;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ServicePoint;
use App\Models\User;
use Illuminate\Http\Request;

class OccurrenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Occurrence::with([
            'user:id,name', // Apenas id e nome do usuário
            'client:id,name',
            'servicePoint:id,name,contract_id',
            'servicePoint.contract:id,name' // Para pegar o nome do contrato
        ])->latest();

        // Filtering
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('contract_id')) {
            // Filtrar ocorrências onde o service_point pertence ao contrato especificado
            $query->whereHas('servicePoint', function ($q) use ($request) {
                $q->where('contract_id', $request->contract_id);
            });
        }
        if ($request->filled('service_point_id')) {
            $query->where('service_point_id', $request->service_point_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('occurrence_type')) {
            $query->where('occurrence_type', $request->occurrence_type);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('occurred_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('occurred_at', '<=', $request->date_to);
        }


        $occurrences = $query->paginate(20)->appends($request->query());

        // Data for filters
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $contracts = Contract::orderBy('name')->get(['id', 'name']); // Consider loading with client for display
        $servicePoints = ServicePoint::orderBy('name')->get(['id', 'name']); // Could be dynamically loaded based on client/contract
        $appUsers = User::where('type', 'app_user')->orderBy('name')->get(['id', 'name']);

        // Distinct types and statuses from occurrences table for filter dropdowns
        $occurrenceTypes = Occurrence::select('occurrence_type')->whereNotNull('occurrence_type')->distinct()->pluck('occurrence_type');
        $occurrenceStatuses = Occurrence::select('status')->distinct()->pluck('status');


        return view('admin.occurrences.index', compact(
            'occurrences',
            'clients',
            'contracts',
            'servicePoints',
            'appUsers',
            'occurrenceTypes',
            'occurrenceStatuses'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Occurrence $occurrence)
    {
        $occurrence->load([
            'user',
            'client',
            'servicePoint.contract.client', // Carrega o contrato e o cliente do contrato do posto
            'evidence',
            'answers.question' // Carrega respostas e as perguntas associadas
        ]);
        return view('admin.occurrences.show', compact('occurrence'));
    }
}
