@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ route('web.competences.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mr-4">
            &larr; Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Nouvelle compétence</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('web.competences.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Libellé -->
            <div>
                <label for="label_comp" class="block text-sm font-medium text-gray-700 mb-1">
                    Libellé <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="label_comp"
                    name="label_comp"
                    value="{{ old('label_comp') }}"
                    placeholder="Nom de la compétence"
                    required
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           {{ $errors->has('label_comp') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('label_comp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description_comp" class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea
                    id="description_comp"
                    name="description_comp"
                    rows="4"
                    placeholder="Description de la compétence (optionnel)"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           {{ $errors->has('description_comp') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >{{ old('description_comp') }}</textarea>
                @error('description_comp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                    Créer
                </button>
                <a href="{{ route('web.competences.index') }}"
                   class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 hover:border-gray-400 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
