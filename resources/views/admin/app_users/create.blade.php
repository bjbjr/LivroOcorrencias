<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo Usuário do Aplicativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.app-users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Nome Completo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- CPF -->
                            <div>
                                <x-input-label for="cpf" :value="__('CPF (Opcional)')" />
                                <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf')" placeholder="000.000.000-00"/>
                                <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Telefone (Opcional)')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="(00) 90000-0000"/>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Company Name -->
                            <div class="md:col-span-2">
                                <x-input-label for="company_name" :value="__('Empresa (Opcional)')" />
                                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" />
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('Senha')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <hr class="my-6 border-gray-300 dark:border-gray-700">

                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Associação e Permissões') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <!-- Client -->
                            <div>
                                <x-input-label for="client_id" :value="__('Cliente Principal (Opcional)')" />
                                <select id="client_id" name="client_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">{{ __('Nenhum cliente principal') }}</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Se um cliente for selecionado, o usuário poderá ser vinculado a postos deste cliente.')}}</p>
                            </div>

                            <!-- Service Point -->
                            <div>
                                <x-input-label for="service_point_id" :value="__('Posto de Serviço Específico (Opcional)')" />
                                <select id="service_point_id" name="service_point_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" disabled>
                                    <option value="">{{ __('Selecione um cliente primeiro ou nenhum posto específico') }}</option>
                                    {{-- Options will be populated by JavaScript --}}
                                </select>
                                <x-input-error :messages="$errors->get('service_point_id')" class="mt-2" />
                                 <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Se um posto for selecionado, o usuário terá acesso apenas a este posto.')}}</p>
                            </div>
                        </div>

                        <!-- Approval Status -->
                        <div class="mt-4">
                            <x-input-label for="approval_status" :value="__('Status da Aprovação')" />
                            <select id="approval_status" name="approval_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="pending" {{ old('approval_status', 'pending') == 'pending' ? 'selected' : '' }}>{{ __('Pendente') }}</option>
                                <option value="approved" {{ old('approval_status') == 'approved' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                                <option value="rejected" {{ old('approval_status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejeitado') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('approval_status')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.app-users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                {{ __('Salvar Usuário') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clientIdSelect = document.getElementById('client_id');
        const servicePointIdSelect = document.getElementById('service_point_id');
        const servicePointApiUrl = "{{ route('admin.api.clients.service-points', ['client' => ':clientId']) }}";

        function fetchServicePoints(selectedClientId) {
            servicePointIdSelect.innerHTML = '<option value="">{{ __('Carregando postos...') }}</option>';
            servicePointIdSelect.disabled = true;

            if (!selectedClientId) {
                servicePointIdSelect.innerHTML = '<option value="">{{ __('Selecione um cliente primeiro ou nenhum posto específico') }}</option>';
                return;
            }

            fetch(servicePointApiUrl.replace(':clientId', selectedClientId))
                .then(response => response.json())
                .then(data => {
                    servicePointIdSelect.innerHTML = '<option value="">{{ __('Nenhum posto específico (acesso a todos do cliente)') }}</option>';
                    if (data.length > 0) {
                        data.forEach(sp => {
                            const option = document.createElement('option');
                            option.value = sp.id;
                            option.textContent = sp.name;
                            servicePointIdSelect.appendChild(option);
                        });
                    } else {
                         servicePointIdSelect.innerHTML = '<option value="">{{ __('Nenhum posto cadastrado para este cliente') }}</option>';
                    }
                    servicePointIdSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching service points:', error);
                    servicePointIdSelect.innerHTML = '<option value="">{{ __('Erro ao carregar postos') }}</option>';
                });
        }

        if (clientIdSelect) {
            clientIdSelect.addEventListener('change', function () {
                fetchServicePoints(this.value);
            });

            // Initial load if a client is already selected (e.g., from old input)
            if (clientIdSelect.value) {
                // fetchServicePoints(clientIdSelect.value); // This will be handled by edit form logic
            } else {
                 servicePointIdSelect.disabled = true;
            }
        }
    });
</script>
@endpush
</x-app-layout>
