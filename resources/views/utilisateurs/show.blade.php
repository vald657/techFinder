@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.utilisateurs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détail de l'utilisateur</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
            <h2 class="text-xl font-semibold text-blue-800">
                {{ $utilisateur->prenom_user }} {{ $utilisateur->nom_user }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">Code : {{ $utilisateur->code_user }}</p>
        </div>

        <div class="px-6 py-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Nom</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $utilisateur->nom_user }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Prénom</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $utilisateur->prenom_user }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Login</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $utilisateur->login_user }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Téléphone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $utilisateur->tel_user ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Sexe</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $utilisateur->sexe_user === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">Rôle</dt>
                    <dd class="mt-1">
                        @php
                            $roleColors = ['admin' => 'bg-purple-100 text-purple-700', 'technicien' => 'bg-blue-100 text-blue-700', 'client' => 'bg-gray-100 text-gray-700'];
                            $roleColor = $roleColors[$utilisateur->role_user] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColor }}">
                            {{ ucfirst($utilisateur->role_user) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide">État</dt>
                    <dd class="mt-1">
                        @php
                            $etatColors = ['actif' => 'bg-green-100 text-green-700', 'inactif' => 'bg-yellow-100 text-yellow-700', 'bloquer' => 'bg-red-100 text-red-700'];
                            $etatColor = $etatColors[$utilisateur->etat_user] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $etatColor }}">
                            {{ ucfirst($utilisateur->etat_user) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center space-x-3">
            <a href="{{ route('web.utilisateurs.edit', $utilisateur->code_user) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Éditer
            </a>
            <form action="{{ route('web.utilisateurs.destroy', $utilisateur->code_user) }}" method="POST" class="inline"
                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Supprimer
                </button>
            </form>
            <a href="{{ route('web.utilisateurs.index') }}"
               class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection
