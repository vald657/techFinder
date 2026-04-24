@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Interventions</h1>
    <a href="{{ route('web.interventions.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
        + Nouvelle intervention
    </a>
</div>

{{-- Barre de recherche et filtres --}}
<form method="GET" action="{{ route('web.interventions.index') }}"
      class="bg-white rounded-lg shadow px-4 py-3 mb-4 flex flex-wrap gap-3 items-end">

    <div class="flex-1 min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Recherche</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Client, technicien, compétence, commentaire…"
               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="min-w-[200px]">
        <label class="block text-xs font-medium text-gray-500 mb-1">Trier par</label>
        <select name="sort"
                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="recent"    {{ request('sort','recent') === 'recent'    ? 'selected' : '' }}>Date décroissante (récent)</option>
            <option value="ancien"    {{ request('sort') === 'ancien'    ? 'selected' : '' }}>Date croissante (ancien)</option>
            <option value="note_desc" {{ request('sort') === 'note_desc' ? 'selected' : '' }}>Note décroissante</option>
            <option value="note_asc"  {{ request('sort') === 'note_asc'  ? 'selected' : '' }}>Note croissante</option>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            Filtrer
        </button>
        @if(request('search') || request('sort'))
            <a href="{{ route('web.interventions.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Réinitialiser
            </a>
        @endif
    </div>
</form>

@if(request('search'))
    <p class="text-sm text-gray-500 mb-3">
        {{ $intervention_list->total() }} résultat(s) pour
        <span class="font-medium text-gray-700">"{{ request('search') }}"</span>
    </p>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technicien</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compétence</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($intervention_list as $intervention)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $intervention->date_int ? \Carbon\Carbon::parse($intervention->date_int)->format('d/m/Y') : '—' }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $intervention->client ? $intervention->client->prenom_user . ' ' . $intervention->client->nom_user : '—' }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $intervention->technicien ? $intervention->technicien->prenom_user . ' ' . $intervention->technicien->nom_user : '—' }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $intervention->competence ? $intervention->competence->label_comp : '—' }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                        @if($intervention->note_int !== null)
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $intervention->note_int >= 14 ? 'bg-green-100 text-green-700' : ($intervention->note_int >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $intervention->note_int }}/20
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                        {{ $intervention->commentaire_int ?? '—' }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('web.interventions.show', $intervention->code_int) }}"
                           class="text-blue-600 hover:text-blue-800 transition-colors">Voir</a>
                        <a href="{{ route('web.interventions.edit', $intervention->code_int) }}"
                           class="text-yellow-600 hover:text-yellow-800 transition-colors">Éditer</a>
                        <form action="{{ route('web.interventions.destroy', $intervention->code_int) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer cette intervention ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 text-sm">
                        Aucune intervention trouvée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($intervention_list->hasPages())
    <div class="mt-4">{{ $intervention_list->links() }}</div>
@endif
@endsection
