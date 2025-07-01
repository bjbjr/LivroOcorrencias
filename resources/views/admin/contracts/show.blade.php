<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Contrato') }}: {{ $contract->name }}
            </h2>
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded dark:bg-yellow-600 dark:hover:bg-yellow-800">
                {{ __('Editar Contrato') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Nome do Contrato') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $contract->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Código do Contrato') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $contract->contract_code }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Cliente') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <a href="{{ route('admin.clients.show', $contract->client) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    {{ $contract->client->name }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Status') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($contract->status)
                                        @case('active') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 @break
                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100 @break
                                        @case('inactive') bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @break
                                        @case('expired') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                    @endswitch">
                                    {{ __(ucfirst($contract->status)) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Data de Início') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $contract->start_date ? $contract->start_date->format('d/m/Y') : '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Data de Fim') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : '--' }}</p>
                        </div>
                        <div class="md:col-span-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Detalhes') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $contract->details ?: '--' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Postos de Serviço Vinculados a Este Contrato') }} ({{ $contract->servicePoints->count() }})
                        </h3>
                        <a href="{{ route('admin.service-points.create', ['contract_id' => $contract->id]) }}" class="px-3 py-1.5 bg-green-500 hover:bg-green-700 text-white text-sm font-bold rounded dark:bg-green-700 dark:hover:bg-green-900">
                            {{ __('Novo Posto para este Contrato') }}
                        </a>
                    </div>
                    @if($contract->servicePoints->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Nenhum posto de serviço vinculado a este contrato.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Nome do Posto') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Código Interno') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Endereço') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Ações') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($contract->servicePoints as $servicePoint)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $servicePoint->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $servicePoint->internal_code ?: '--' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($servicePoint->address, 50) ?: '--' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.service-points.show', $servicePoint) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">{{ __('Ver') }}</a>
                                                <a href="{{ route('admin.service-points.edit', $servicePoint) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200">{{ __('Editar') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.contracts.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">
                    &larr; {{ __('Voltar para todos os contratos') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
