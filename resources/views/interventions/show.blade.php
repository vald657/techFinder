@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.interventions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détail de l'intervention</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
            <h2 class="text-xl font-semibold text-blue-800">
                Intervention #{{ $intervention->code_int }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Date : {{ $intervention->date_int ? \Carbon\Carbon::parse($intervention->date_int)->format('d/m/Y') : '—' }}
            </p>
        </div>

        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Client</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $intervention->client ? $intervention->client->prenom_user . ' ' . $intervention->client->nom_user : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Technicien</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $intervention->technicien ? $intervention->technicien->prenom_user . ' ' . $intervention->technicien->nom_user : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Compétence</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $intervention->competence ? $intervention->competence->label_comp : '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Note</dt>
                    <dd class="mt-1">
                        @if($intervention->note_int !== null)
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $intervention->note_int >= 14 ? 'bg-green-100 text-green-700' : ($intervention->note_int >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $intervention->note_int }}/20
                            </span>
                        @else
                            <span class="text-sm text-gray-400">Non notée</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Commentaire</dt>
                    <dd class="mt-1 text-sm text-gray-700">
                        {{ $intervention->commentaire_int ?? 'Aucun commentaire.' }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center space-x-3">
            <a href="{{ route('web.interventions.edit', $intervention->code_int) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Éditer
            </a>
            <form action="{{ route('web.interventions.destroy', $intervention->code_int) }}" method="POST" class="inline"
                  onsubmit="return confirm('Supprimer cette intervention ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
            <a href="{{ route('web.interventions.index') }}"
               class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
