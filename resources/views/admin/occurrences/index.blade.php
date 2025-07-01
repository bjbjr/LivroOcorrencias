<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Visualizar Ocorrências') }}
            </h2>
            {{-- Botão de Nova Ocorrência pode ser adicionado aqui se/quando implementado no admin --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- Changed to max-w-full for wider table --}}
            @include('admin.partials.flash-messages')

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.occurrences.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                            <div>
                                <x-input-label for="client_id_filter" :value="__('Cliente')" />
                                <select name="client_id" id="client_id_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="contract_id_filter" :value="__('Contrato')" />
                                <select name="contract_id" id="contract_id_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                    @foreach ($contracts as $contract) {{-- Assuming contracts are passed with client names for better context if not dynamically loaded --}}
                                        <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>{{ $contract->name }} {{ isset($contract->client) ? '('.$contract->client->name.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="service_point_id_filter" :value="__('Posto de Serviço')" />
                                <select name="service_point_id" id="service_point_id_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                    {{-- This could be dynamically populated based on client/contract --}}
                                    @foreach ($servicePoints as $servicePoint)
                                        <option value="{{ $servicePoint->id }}" {{ request('service_point_id') == $servicePoint->id ? 'selected' : '' }}>{{ $servicePoint->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="user_id_filter" :value="__('Usuário App')" />
                                <select name="user_id" id="user_id_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                    @foreach ($appUsers as $appUser)
                                        <option value="{{ $appUser->id }}" {{ request('user_id') == $appUser->id ? 'selected' : '' }}>{{ $appUser->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="status_filter" :value="__('Status Ocorrência')" />
                                <select name="status" id="status_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                     @foreach ($occurrenceStatuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ __(ucfirst($status)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="occurrence_type_filter" :value="__('Tipo Ocorrência')" />
                                <select name="occurrence_type" id="occurrence_type_filter" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="">{{ __('Todos') }}</option>
                                     @foreach ($occurrenceTypes as $type)
                                        <option value="{{ $type }}" {{ request('occurrence_type') == $type ? 'selected' : '' }}>{{ __(ucfirst(str_replace('_', ' ', $type))) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="date_from_filter" :value="__('De (Data Ocorrência)')" />
                                <x-text-input type="date" name="date_from" id="date_from_filter" value="{{ request('date_from') }}" class="mt-1 block w-full text-sm" />
                            </div>
                             <div>
                                <x-input-label for="date_to_filter" :value="__('Até (Data Ocorrência)')" />
                                <x-text-input type="date" name="date_to" id="date_to_filter" value="{{ request('date_to') }}" class="mt-1 block w-full text-sm" />
                            </div>
                            <div class="flex items-end">
                                <x-primary-button type="submit" class="text-sm">{{ __('Filtrar') }}</x-primary-button>
                                <a href="{{ route('admin.occurrences.index') }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">{{ __('Limpar') }}</a>
                            </div>
                        </div>
                    </form>

                    @if($occurrences->isEmpty())
                        <p>{{ __('Nenhuma ocorrência encontrada para os filtros aplicados.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Título/Tipo') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Cliente') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Posto (Contrato)') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Registrado Por') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Data Ocorrência') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Registrado Em') }}</th>
                                        <th class="relative px-4 py-3"><span class="sr-only">{{ __('Ações') }}</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($occurrences as $occurrence)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $occurrence->id }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ Str::limit($occurrence->title ?: __('Ocorrência'), 30) }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __(ucfirst(str_replace('_', ' ', $occurrence->occurrence_type))) }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $occurrence->client->name ?? '--' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $occurrence->servicePoint->name ?? '--' }}
                                                @if($occurrence->servicePoint && $occurrence->servicePoint->contract)
                                                    <div class="text-xs text-gray-400 dark:text-gray-500">({{ $occurrence->servicePoint->contract->name }})</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $occurrence->user->name ?? '--' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{-- TODO: Add status colors --}}">
                                                    {{ __(ucfirst($occurrence->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $occurrence->occurred_at ? \Carbon\Carbon::parse($occurrence->occurred_at)->format('d/m/Y H:i') : '--' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $occurrence->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.occurrences.show', $occurrence) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">{{ __('Detalhes') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $occurrences->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
