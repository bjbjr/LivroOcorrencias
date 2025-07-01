@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 border border-green-400 dark:border-green-700 rounded shadow-sm" role="alert">
        <strong class="font-bold">{{ __('Sucesso!') }}</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 border border-red-400 dark:border-red-700 rounded shadow-sm" role="alert">
        <strong class="font-bold">{{ __('Erro!') }}</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 border border-yellow-400 dark:border-yellow-700 rounded shadow-sm" role="alert">
        <strong class="font-bold">{{ __('Atenção!') }}</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 border border-blue-400 dark:border-blue-700 rounded shadow-sm" role="alert">
        <strong class="font-bold">{{ __('Informação:') }}</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 border border-red-400 dark:border-red-700 rounded shadow-sm" role="alert">
        <strong class="font-bold">{{ __('Ops! Algo deu errado.') }}</strong>
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
