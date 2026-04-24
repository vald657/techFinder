@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.user-competences.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détail de l'association</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
            <h2 class="text-xl font-semibold text-blue-800">
                Association Utilisateur–Compétence
            </h2>
        </div>

        <div class="px-6 py-5">
            <dl class="space-y-4">
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Utilisateur</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($usercompetence->utilisateur)
                            <span class="font-medium">
                                {{ $usercompetence->utilisateur->prenom_user }} {{ $usercompetence->utilisateur->nom_user }}
                            </span>
                            <span class="ml-2 text-gray-500 text-xs">
                                ({{ ucfirst($usercompetence->utilisateur->role_user) }} — {{ $usercompetence->utilisateur->login_user }})
                            </span>
                        @else
                            <span class="text-gray-400">Code : {{ $usercompetence->code_user }}</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Compétence</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($usercompetence->competence)
                            <span class="font-medium">{{ $usercompetence->competence->label_comp }}</span>
                            @if($usercompetence->competence->description_comp)
                                <p class="mt-1 text-xs text-gray-500">{{ $usercompetence->competence->description_comp }}</p>
                            @endif
                        @else
                            <span class="text-gray-400">Code : {{ $usercompetence->code_comp }}</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center space-x-3">
            <a href="{{ route('web.user-competences.edit', $usercompetence) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Éditer
            </a>
            <form action="{{ route('web.user-competences.destroy', $usercompetence) }}" method="POST" class="inline"
                  onsubmit="return confirm('Supprimer cette association ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
            <a href="{{ route('web.user-competences.index') }}"
               class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
