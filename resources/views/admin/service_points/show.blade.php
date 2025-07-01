<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Posto de Serviço') }}: {{ $servicePoint->name }}
            </h2>
            <a href="{{ route('admin.service-points.edit', $servicePoint) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded dark:bg-yellow-600 dark:hover:bg-yellow-800">
                {{ __('Editar Posto') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Nome do Posto') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $servicePoint->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Contrato Associado') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <a href="{{ route('admin.contracts.show', $servicePoint->contract) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">
                                    {{ $servicePoint->contract->name }}
                                </a>
                                <span class="text-xs text-gray-500 dark:text-gray-500">({{ $servicePoint->contract->client->name }})</span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Código Interno') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $servicePoint->internal_code ?: '--' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Endereço') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $servicePoint->address ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Latitude') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $servicePoint->latitude ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Longitude') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $servicePoint->longitude ?: '--' }}</p>
                        </div>
                        @if($servicePoint->latitude && $servicePoint->longitude)
                        <div class="md:col-span-3">
                             <a href="https://www.google.com/maps?q={{ $servicePoint->latitude }},{{ $servicePoint->longitude }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                {{ __('Ver no Google Maps') }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Usuários do App Associados a Este Posto') }}</h3>
                        @if($servicePoint->users->where('type', 'app_user')->count() > 0)
                            <ul class="list-disc list-inside mt-2 text-sm text-gray-600 dark:text-gray-400">
                                @foreach($servicePoint->users->where('type', 'app_user') as $user)
                                    <li>{{ $user->name }} ({{ $user->email }}) - Status: {{ $user->approval_status }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Nenhum usuário de aplicativo associado diretamente a este posto.') }}</p>
                        @endif
                    </div>

                     <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Ocorrências Registradas Neste Posto') }}</h3>
                        @if($servicePoint->occurrences->count() > 0)
                            <ul class="list-disc list-inside mt-2 text-sm text-gray-600 dark:text-gray-400">
                                @foreach($servicePoint->occurrences as $occurrence)
                                    {{-- TODO: Update this link when admin occurrence view is ready --}}
                                    <li><a href="#" class="text-blue-500 hover:text-blue-700">{{ $occurrence->title ?: "Ocorrência #".$occurrence->id }}</a> - Status: {{ $occurrence->status }} (Em: {{ $occurrence->created_at->format('d/m/Y H:i') }})</li>
                                @endforeach
                            </ul>
                        @else
                             <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Nenhuma ocorrência registrada para este posto.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.service-points.index', ['contract_id' => $servicePoint->contract_id]) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">
                    &larr; {{ __('Voltar para postos de serviço') }} @if($servicePoint->contract) {{ __('do contrato ') . $servicePoint->contract->name }} @endif
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
