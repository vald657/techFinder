@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Associations Utilisateur–Compétence</h1>
    <a href="{{ route('web.user-competences.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
        + Nouvelle association
    </a>
</div>

{{-- Barre de recherche et filtres --}}
<form method="GET" action="{{ route('web.user-competences.index') }}"
      class="bg-white rounded-lg shadow px-4 py-3 mb-4 flex flex-wrap gap-3 items-end">

    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Nom d'utilisateur ou compétence…"
               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Trier par</label>
        <select name="sort"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="recent"  {{ request('sort','recent') === 'recent'  ? 'selected' : '' }}>Plus récent</option>
            <option value="ancien"  {{ request('sort') === 'ancien'  ? 'selected' : '' }}>Plus ancien</option>
            <option value="az_user" {{ request('sort') === 'az_user' ? 'selected' : '' }}>Utilisateur A → Z</option>
            <option value="az_comp" {{ request('sort') === 'az_comp' ? 'selected' : '' }}>Compétence A → Z</option>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            Filtrer
        </button>
        @if(request('search') || request('sort'))
            <a href="{{ route('web.user-competences.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Réinitialiser
            </a>
        @endif
    </div>
</form>

@if(request('search'))
    <p class="text-sm text-gray-500 mb-3">
        {{ $usercompetence_list->total() }} résultat(s) pour
        <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
    </p>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compétence</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($usercompetence_list as $uc)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $uc->utilisateur ? $uc->utilisateur->prenom_user . ' ' . $uc->utilisateur->nom_user : $uc->code_user }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $uc->competence ? $uc->competence->label_comp : $uc->code_comp }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('web.user-competences.show', $uc->code_user . '_' . $uc->code_comp) }}"
                           class="text-blue-600 hover:text-blue-800 transition-colors">Voir</a>
                        <a href="{{ route('web.user-competences.edit', $uc->code_user . '_' . $uc->code_comp) }}"
                           class="text-yellow-600 hover:text-yellow-800 transition-colors">Éditer</a>
                        <form action="{{ route('web.user-competences.destroy', $uc->code_user . '_' . $uc->code_comp) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cette association ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 text-sm">
                        Aucune association trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($usercompetence_list->hasPages())
    <div class="mt-4">{{ $usercompetence_list->links() }}</div>
@endif
@endsection
