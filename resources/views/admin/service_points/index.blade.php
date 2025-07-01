<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gerenciar Postos de Serviço') }}
            </h2>
            <a href="{{ route('admin.service-points.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded dark:bg-blue-700 dark:hover:bg-blue-900">
                {{ __('Novo Posto de Serviço') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 border border-green-400 dark:border-green-600 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 border border-red-400 dark:border-red-600 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 border border-yellow-400 dark:border-yellow-600 rounded">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filter by Client -->
                    <!-- Filter -->
                    <form method="GET" action="{{ route('admin.service-points.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
                        <div>
                            <x-input-label for="client_id_filter" :value="__('Filtrar por Cliente')" />
                            <select name="client_id_filter" id="client_id_filter" class="mt-1 block w-full md:w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('Todos os Clientes') }}</option>
                                @foreach ($clients as $clientFilter) {{-- Renamed to avoid conflict with $client in loop --}}
                                    <option value="{{ $clientFilter->id }}" {{ request('client_id_filter') == $clientFilter->id ? 'selected' : '' }}>
                                        {{ $clientFilter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="contract_id_filter" :value="__('Filtrar por Contrato')" />
                            <select name="contract_id" id="contract_id_filter" class="mt-1 block w-full md:w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('Todos os Contratos') }}</option>
                                {{-- This dropdown could be populated dynamically based on client selection or list all --}}
                                @foreach ($contracts as $contractFilter) {{-- Renamed to avoid conflict --}}
                                     <option value="{{ $contractFilter->id }}" {{ request('contract_id') == $contractFilter->id ? 'selected' : '' }}>
                                        {{ $contractFilter->name }} ({{ $contractFilter->client->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <x-primary-button type="submit">{{ __('Filtrar') }}</x-primary-button>
                        <a href="{{ route('admin.service-points.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white ml-2">{{ __('Limpar Filtros') }}</a>
                    </form>

                    @if($servicePoints->isEmpty())
                        <p>{{ __('Nenhum posto de serviço cadastrado ainda.') }}
                            @if(request('client_id_filter') || request('contract_id'))
                                {{__('para os filtros aplicados.')}}
                            @endif
                            <a href="{{ route('admin.service-points.create')}}" class="text-blue-500 hover:text-blue-700">{{__('Adicionar um?')}}</a>
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Nome do Posto') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Contrato (Cliente)') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Código Interno') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Localização') }}</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Ações') }}</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($servicePoints as $servicePoint)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $servicePoint->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-300">
                                                    <a href="{{ route('admin.contracts.show', $servicePoint->contract) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                                        {{ $servicePoint->contract->name }}
                                                    </a>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">({{ $servicePoint->contract->client->name }})</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $servicePoint->internal_code ?: '--' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                @if($servicePoint->latitude && $servicePoint->longitude)
                                                    <a href="https://www.google.com/maps?q={{ $servicePoint->latitude }},{{ $servicePoint->longitude }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                                        Ver Mapa
                                                    </a>
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.service-points.show', $servicePoint) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">{{ __('Ver') }}</a>
                                                <a href="{{ route('admin.service-points.edit', $servicePoint) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200 mr-2">{{ __('Editar') }}</a>
                                                <form action="{{ route('admin.service-points.destroy', $servicePoint) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Tem certeza que deseja excluir este posto de serviço? Esta ação não pode ser desfeita.') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">{{ __('Excluir') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $servicePoints->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
