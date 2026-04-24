@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.user-competences.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle association Utilisateur–Compétence</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('web.user-competences.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Utilisateur -->
            <div>
                <label for="code_user" class="block text-sm font-medium text-gray-700 mb-1">
                    Utilisateur <span class="text-red-500">*</span>
                </label>
                <select id="code_user" name="code_user" required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('code_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">Sélectionner un utilisateur...</option>
                    @foreach($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->code_user }}"
                                {{ old('code_user') == $utilisateur->code_user ? 'selected' : '' }}>
                            {{ $utilisateur->prenom_user }} {{ $utilisateur->nom_user }}
                            ({{ ucfirst($utilisateur->role_user) }})
                        </option>
                    @endforeach
                </select>
                @error('code_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Compétence -->
            <div>
                <label for="code_comp" class="block text-sm font-medium text-gray-700 mb-1">
                    Compétence <span class="text-red-500">*</span>
                </label>
                <select id="code_comp" name="code_comp" required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('code_comp') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">Sélectionner une compétence...</option>
                    @foreach($competences as $competence)
                        <option value="{{ $competence->code_comp }}"
                                {{ old('code_comp') == $competence->code_comp ? 'selected' : '' }}>
                            {{ $competence->label_comp }}
                        </option>
                    @endforeach
                </select>
                @error('code_comp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    Créer
                </button>
                <a href="{{ route('web.user-competences.index') }}"
                   class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
