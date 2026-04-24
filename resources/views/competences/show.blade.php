@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.competences.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détail de la compétence</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
            <h2 class="text-xl font-semibold text-blue-800">{{ $competence->label_comp }}</h2>
            <p class="text-xs text-gray-500 mt-1">Code : {{ $competence->code_comp }}</p>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div>
                <span class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Description</span>
                <p class="text-gray-700">
                    {{ $competence->description_comp ?? 'Aucune description renseignée.' }}
                </p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center space-x-3">
            <a href="{{ route('web.competences.edit', $competence->code_comp) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Éditer
            </a>
            <form action="{{ route('web.competences.destroy', $competence->code_comp) }}" method="POST" class="inline"
                  onsubmit="return confirm('Supprimer cette compétence ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
            <a href="{{ route('web.competences.index') }}"
               class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
