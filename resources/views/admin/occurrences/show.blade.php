<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes da Ocorrência') }} #{{ $occurrence->id }}
            </h2>
            <a href="{{ route('admin.occurrences.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200">
                &larr; {{ __('Voltar para todas as ocorrências') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Max width can be adjusted --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Título da Ocorrência') }}</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $occurrence->title ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tipo de Ocorrência') }}</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $occurrence->occurrence_type))) ?: '--' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</h3>
                            <p class="mt-1 text-lg">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{-- TODO: Add status colors --}}">
                                    {{ __(ucfirst($occurrence->status)) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Data do Evento') }}</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $occurrence->occurred_at ? \Carbon\Carbon::parse($occurrence->occurred_at)->format('d/m/Y H:i:s') : '--' }}</p>
                        </div>
                         <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Registrado Por') }}</h3>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $occurrence->user->name ?? '--' }} ({{ $occurrence->user->email ?? '--' }})</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Data de Registro no Sistema') }}</h3>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $occurrence->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Cliente') }}</h3>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                <a href="{{ $occurrence->client ? route('admin.clients.show', $occurrence->client) : '#' }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    {{ $occurrence->client->name ?? '--' }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Posto de Serviço') }}</h3>
                             @if($occurrence->servicePoint)
                                <p class="mt-1 text-gray-900 dark:text-white">
                                   <a href="{{ route('admin.service-points.show', $occurrence->servicePoint) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        {{ $occurrence->servicePoint->name ?? '--' }}
                                   </a>
                                </p>
                                @if($occurrence->servicePoint->contract)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Contrato:
                                    <a href="{{ route('admin.contracts.show', $occurrence->servicePoint->contract) }}" class="text-blue-500 hover:text-blue-700">
                                        {{ $occurrence->servicePoint->contract->name }}
                                    </a>
                                </p>
                                @endif
                            @else
                                <p class="mt-1 text-gray-900 dark:text-white">--</p>
                            @endif
                        </div>
                        @if($occurrence->internal_reporter_name)
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nome do Relator Interno (se aplicável)') }}</h3>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $occurrence->internal_reporter_name }}</p>
                        </div>
                        @endif
                    </div>

                    @if($occurrence->description)
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-2">{{ __('Descrição Geral') }}</h3>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $occurrence->description }}</p>
                    </div>
                    @endif

                    @if($occurrence->involved_parties && count(json_decode($occurrence->involved_parties, true)) > 0)
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-2">{{ __('Partes Envolvidas') }}</h3>
                        @php $parties = json_decode($occurrence->involved_parties, true); @endphp
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                            @foreach($parties as $party)
                                <li>{{ $party['name'] ?? 'Nome não informado' }} - {{ $party['role'] ?? 'Papel não informado' }} ({{ $party['contact'] ?? 'Contato não informado' }})</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($occurrence->answers && $occurrence->answers->count() > 0)
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-2">{{ __('Respostas às Perguntas do Fluxo') }}</h3>
                        <dl class="space-y-4">
                            @foreach($occurrence->answers as $answer)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md">
                                    <dt class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $answer->question->question_text ?? 'Pergunta não encontrada' }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $answer->answer_text ?: (is_array(json_decode($answer->selected_options, true)) ? implode(', ', array_column(json_decode($answer->selected_options, true), 'label')) : ($answer->selected_options ?: '--')) }}
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                    @endif

                    @if($occurrence->evidence && $occurrence->evidence->count() > 0)
                    <div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">{{ __('Evidências Anexadas') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($occurrence->evidence as $ev)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md shadow">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ __(ucfirst($ev->type)) }}</p>
                                    @if(in_array($ev->type, ['photo', 'video', 'audio']))
                                        <a href="{{ Storage::url($ev->content_path) }}" target="_blank" class="mt-1 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 break-all">
                                            {{ $ev->original_filename ?: $ev->content_path }}
                                        </a>
                                        @if($ev->type == 'photo')
                                            <img src="{{ Storage::url($ev->content_path) }}" alt="{{ $ev->original_filename }}" class="mt-2 rounded-md max-h-48 w-auto object-contain">
                                        @endif
                                    @else {{-- text --}}
                                        <p class="mt-1 text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">{{ Str::limit($ev->content_path, 150) }}</p>
                                        @if(strlen($ev->content_path) > 150)
                                         {{-- TODO: Add a modal or expander for long text evidence --}}
                                        @endif
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $ev->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
