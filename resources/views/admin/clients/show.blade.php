<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Cliente') }}: {{ $client->name }}
            </h2>
            <a href="{{ route('admin.clients.edit', $client) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded dark:bg-yellow-600 dark:hover:bg-yellow-800">
                {{ __('Editar Cliente') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Nome') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $client->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Pessoa de Contato') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $client->contact_person ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Email de Contato') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $client->contact_email ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Telefone de Contato') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $client->contact_phone ?: '--' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Endereço') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $client->address ?: '--' }}</p>
                        </div>
                         @if($client->logo_path)
                        <div class="md:col-span-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('Logo') }}</h3>
                            <img src="{{ Storage::url($client->logo_path) }}" alt="Logo {{ $client->name }}" class="h-32 w-auto rounded object-cover">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ __('Postos de Serviço Associados') }} ({{ $client->servicePoints->count() }})
                        </h3>
                        <a href="{{ route('admin.service-points.create', ['client_id' => $client->id]) }}" class="px-3 py-1.5 bg-green-500 hover:bg-green-700 text-white text-sm font-bold rounded dark:bg-green-700 dark:hover:bg-green-900">
                            {{ __('Novo Posto para este Cliente') }}
                        </a>
                    </div>
                    @if($client->servicePoints->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Nenhum posto de serviço associado a este cliente.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Nome do Posto') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Código Interno') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Ações') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($client->servicePoints as $servicePoint)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $servicePoint->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $servicePoint->internal_code ?: '--' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.service-points.show', $servicePoint) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">{{ __('Ver') }}</a>
                                                <a href="{{ route('admin.service-points.edit', $servicePoint) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-200">{{ __('Editar') }}</a>
                                                {{-- Delete form can be added here if needed --}}
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
                <a href="{{ route('admin.clients.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">
                    &larr; {{ __('Voltar para todos os clientes') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
