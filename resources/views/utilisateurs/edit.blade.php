@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.utilisateurs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Modifier l'utilisateur</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('web.utilisateurs.update', $utilisateur->code_user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nom -->
                <div>
                    <label for="nom_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nom_user" name="nom_user" value="{{ old('nom_user', $utilisateur->nom_user) }}" required
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  {{ $errors->has('nom_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('nom_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="prenom_user" name="prenom_user" value="{{ old('prenom_user', $utilisateur->prenom_user) }}" required
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  {{ $errors->has('prenom_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('prenom_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Login -->
            <div>
                <label for="login_user" class="block text-sm font-medium text-gray-700 mb-1">
                    Login <span class="text-red-500">*</span>
                </label>
                <input type="text" id="login_user" name="login_user" value="{{ old('login_user', $utilisateur->login_user) }}" required
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              {{ $errors->has('login_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('login_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password_user" class="block text-sm font-medium text-gray-700 mb-1">
                    Nouveau mot de passe <span class="text-gray-400 text-xs">(laisser vide pour ne pas changer)</span>
                </label>
                <input type="password" id="password_user" name="password_user"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              {{ $errors->has('password_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('password_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Confirmation mot de passe -->
            <div>
                <label for="password_user_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirmer le nouveau mot de passe <span class="text-gray-400 text-xs">(si changement)</span>
                </label>
                <input type="password" id="password_user_confirmation" name="password_user_confirmation"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300">
            </div>

            <!-- Téléphone -->
            <div>
                <label for="tel_user" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" id="tel_user" name="tel_user" value="{{ old('tel_user', $utilisateur->tel_user) }}"
                       class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              {{ $errors->has('tel_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('tel_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Sexe -->
                <div>
                    <label for="sexe_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Sexe <span class="text-red-500">*</span>
                    </label>
                    <select id="sexe_user" name="sexe_user" required
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   {{ $errors->has('sexe_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">Choisir...</option>
                        <option value="M" {{ old('sexe_user', $utilisateur->sexe_user) === 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe_user', $utilisateur->sexe_user) === 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Rôle <span class="text-red-500">*</span>
                    </label>
                    <select id="role_user" name="role_user" required
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   {{ $errors->has('role_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">Choisir...</option>
                        <option value="client" {{ old('role_user', $utilisateur->role_user) === 'client' ? 'selected' : '' }}>Client</option>
                        <option value="admin" {{ old('role_user', $utilisateur->role_user) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="technicien" {{ old('role_user', $utilisateur->role_user) === 'technicien' ? 'selected' : '' }}>Technicien</option>
                    </select>
                    @error('role_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- État -->
                <div>
                    <label for="etat_user" class="block text-sm font-medium text-gray-700 mb-1">
                        État <span class="text-red-500">*</span>
                    </label>
                    <select id="etat_user" name="etat_user" required
                            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   {{ $errors->has('etat_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">Choisir...</option>
                        <option value="actif" {{ old('etat_user', $utilisateur->etat_user) === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('etat_user', $utilisateur->etat_user) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="bloquer" {{ old('etat_user', $utilisateur->etat_user) === 'bloquer' ? 'selected' : '' }}>Bloqué</option>
                    </select>
                    @error('etat_user') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    Mettre à jour
                </button>
                <a href="{{ route('web.utilisateurs.show', $utilisateur->code_user) }}"
                   class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
