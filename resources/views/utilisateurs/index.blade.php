@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Utilisateurs</h1>
    <a href="{{ route('web.utilisateurs.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
        + Nouvel utilisateur
    </a>
</div>

{{-- Barre de recherche et filtres --}}
<form method="GET" action="{{ route('web.utilisateurs.index') }}"
      class="bg-white rounded-lg shadow px-4 py-3 mb-4 flex flex-wrap gap-3 items-end">

    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Nom, prénom ou login…"
               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="min-w-[140px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Rôle</label>
        <select name="role"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Tous les rôles</option>
            <option value="admin"      {{ request('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
            <option value="technicien" {{ request('role') === 'technicien' ? 'selected' : '' }}>Technicien</option>
            <option value="client"     {{ request('role') === 'client'     ? 'selected' : '' }}>Client</option>
        </select>
    </div>

    <div class="min-w-[140px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">État</label>
        <select name="etat"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Tous les états</option>
            <option value="actif"    {{ request('etat') === 'actif'    ? 'selected' : '' }}>Actif</option>
            <option value="inactif"  {{ request('etat') === 'inactif'  ? 'selected' : '' }}>Inactif</option>
            <option value="bloquer"  {{ request('etat') === 'bloquer'  ? 'selected' : '' }}>Bloqué</option>
        </select>
    </div>

    <div class="min-w-[170px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Trier par</label>
        <select name="sort"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="recent" {{ request('sort','recent') === 'recent' ? 'selected' : '' }}>Plus récent</option>
            <option value="ancien" {{ request('sort') === 'ancien' ? 'selected' : '' }}>Plus ancien</option>
            <option value="az"     {{ request('sort') === 'az'     ? 'selected' : '' }}>Nom A → Z</option>
            <option value="za"     {{ request('sort') === 'za'     ? 'selected' : '' }}>Nom Z → A</option>
        </select>
    </div>

    <div class="flex gap-2">
        <div>
            <label class="block text-xs mb-1 invisible">_</label>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Filtrer
            </button>
        </div>
        @if(request('search') || request('role') || request('etat') || request('sort'))
            <div>
                <label class="block text-xs mb-1 invisible">_</label>
                <a href="{{ route('web.utilisateurs.index') }}"
                   class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Réinitialiser
                </a>
            </div>
        @endif
    </div>
</form>

@if(request('search') || request('role') || request('etat'))
    <p class="text-sm text-gray-500 mb-3">
        {{ $utilisateur_list->total() }} résultat(s)
        @if(request('search'))
            pour <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
        @endif
    </p>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($utilisateur_list as $utilisateur)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $utilisateur->nom_user }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $utilisateur->prenom_user }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $utilisateur->login_user }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $roleColors = ['admin' => 'bg-purple-100 text-purple-700', 'technicien' => 'bg-blue-100 text-blue-700', 'client' => 'bg-gray-100 text-gray-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColors[$utilisateur->role_user] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($utilisateur->role_user) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $etatColors = ['actif' => 'bg-green-100 text-green-700', 'inactif' => 'bg-yellow-100 text-yellow-700', 'bloquer' => 'bg-red-100 text-red-700'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $etatColors[$utilisateur->etat_user] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($utilisateur->etat_user) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('web.utilisateurs.show', $utilisateur->code_user) }}"
                           class="text-blue-600 hover:text-blue-800 transition-colors">Voir</a>
                        <a href="{{ route('web.utilisateurs.edit', $utilisateur->code_user) }}"
                           class="text-yellow-600 hover:text-yellow-800 transition-colors">Éditer</a>
                        <form action="{{ route('web.utilisateurs.destroy', $utilisateur->code_user) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($utilisateur_list->hasPages())
    <div class="mt-4">{{ $utilisateur_list->links() }}</div>
@endif
@endsection
