<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Bem-vindo ao Painel Administrativo!") }}

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Gerenciamento') }}</h3>
                        <ul class="mt-2 list-disc list-inside">
                            <li><a href="{{ route('admin.clients.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">{{ __('Gerenciar Clientes') }}</a></li>
                            <li><a href="{{ route('admin.contracts.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">{{ __('Gerenciar Contratos') }}</a></li>
                            <li><a href="{{ route('admin.service-points.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">{{ __('Gerenciar Postos de Serviço') }}</a></li>
                            <li><a href="{{ route('admin.app-users.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">{{ __('Gerenciar Usuários do App') }}</a></li>
                            <li><a href="{{ route('admin.occurrences.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">{{ __('Visualizar Ocorrências') }}</a></li>
                            {{-- Adicionar links para outras seções administrativas aqui --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
