@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.interventions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle intervention</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('web.interventions.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Date -->
            <div>
                <label for="date_int" class="block text-sm font-medium text-gray-700 mb-1">
                    Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="date_int" name="date_int" value="{{ old('date_int') }}" required
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              {{ $errors->has('date_int') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('date_int') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Client -->
            <div>
                <label for="code_user_client" class="block text-sm font-medium text-gray-700 mb-1">
                    Client <span class="text-red-500">*</span>
                </label>
                <select id="code_user_client" name="code_user_client" required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('code_user_client') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">Sélectionner un client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->code_user }}" {{ old('code_user_client') == $client->code_user ? 'selected' : '' }}>
                            {{ $client->prenom_user }} {{ $client->nom_user }}
                        </option>
                    @endforeach
                </select>
                @error('code_user_client') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Technicien -->
            <div>
                <label for="code_user_techn" class="block text-sm font-medium text-gray-700 mb-1">
                    Technicien <span class="text-red-500">*</span>
                </label>
                <select id="code_user_techn" name="code_user_techn" required
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('code_user_techn') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">Sélectionner un technicien...</option>
                    @foreach($techniciens as $technicien)
                        <option value="{{ $technicien->code_user }}" {{ old('code_user_techn') == $technicien->code_user ? 'selected' : '' }}>
                            {{ $technicien->prenom_user }} {{ $technicien->nom_user }}
                        </option>
                    @endforeach
                </select>
                @error('code_user_techn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                        <option value="{{ $competence->code_comp }}" {{ old('code_comp') == $competence->code_comp ? 'selected' : '' }}>
                            {{ $competence->label_comp }}
                        </option>
                    @endforeach
                </select>
                @error('code_comp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Note -->
            <div>
                <label for="note_int" class="block text-sm font-medium text-gray-700 mb-1">
                    Note <span class="text-gray-400 text-xs">(0–20)</span>
                </label>
                <input type="number" id="note_int" name="note_int" value="{{ old('note_int') }}"
                       min="0" max="20" step="0.5"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              {{ $errors->has('note_int') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('note_int') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Commentaire -->
            <div>
                <label for="commentaire_int" class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                <textarea id="commentaire_int" name="commentaire_int" rows="3"
                          class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 {{ $errors->has('commentaire_int') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('commentaire_int') }}</textarea>
                @error('commentaire_int') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    Créer
                </button>
                <a href="{{ route('web.interventions.index') }}"
                   class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
