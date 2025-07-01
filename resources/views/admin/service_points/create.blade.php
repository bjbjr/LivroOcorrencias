<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Posto de Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('warning'))
                <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-200 border border-yellow-400 dark:border-yellow-600 rounded">
                    {{ session('warning') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.service-points.store') }}" method="POST">
                        @csrf

                        <!-- Contract -->
                        <div class="mt-4">
                            <x-input-label for="contract_id" :value="__('Contrato Associado')" />
                            <select id="contract_id" name="contract_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">{{ __('Selecione um Contrato') }}</option>
                                @foreach ($contracts as $contract)
                                    <option value="{{ $contract->id }}" {{ (old('contract_id', $selectedContractId ?? '') == $contract->id) ? 'selected' : '' }}>
                                        {{ $contract->name }} ({{ $contract->client->name }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('contract_id')" class="mt-2" />
                        </div>

                        <!-- Name -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nome do Posto de Serviço')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Internal Code -->
                        <div class="mt-4">
                            <x-input-label for="internal_code" :value="__('Código Interno (Opcional)')" />
                            <x-text-input id="internal_code" class="block mt-1 w-full" type="text" name="internal_code" :value="old('internal_code')" />
                            <x-input-error :messages="$errors->get('internal_code')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Endereço (Opcional)')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mt-4">
                            <!-- Latitude -->
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude (Opcional)')" />
                                <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude')" placeholder="-23.550520" />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>
                            <!-- Longitude -->
                            <div>
                                <x-input-label for="longitude" :value="__('Longitude (Opcional)')" />
                                <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude')" placeholder="-46.633308" />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.service-points.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Salvar Posto de Serviço') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
