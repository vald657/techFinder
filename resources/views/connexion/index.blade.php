<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TechFinder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">TechFinder</h1>
                <p class="text-gray-500 mt-2">Connectez-vous à votre compte</p>
            </div>

            <!-- Erreurs globales -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-md mb-6">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('web.connexion.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Login -->
                <div>
                    <label for="login_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Identifiant
                    </label>
                    <input
                        type="text"
                        id="login_user"
                        name="login_user"
                        value="{{ old('login_user') }}"
                        placeholder="Votre identifiant"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('login_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('login_user')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password_user" class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe
                    </label>
                    <input
                        type="password"
                        id="password_user"
                        name="password_user"
                        placeholder="Votre mot de passe"
                        class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               {{ $errors->has('password_user') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('password_user')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton de connexion -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Se connecter
                </button>
            </form>
        </div>
    </div>

</body>
</html>
