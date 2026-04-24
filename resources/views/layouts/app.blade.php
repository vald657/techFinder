<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFinder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Barre de navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo / Titre -->
                <div class="flex items-center">
                    <a href="{{ route('web.competences.index') }}" class="text-xl font-bold text-blue-600">
                        TechFinder
                    </a>
                </div>

                <!-- Liens de navigation -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('web.competences.index') }}"
                       class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Compétences
                    </a>
                    <a href="{{ route('web.utilisateurs.index') }}"
                       class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Utilisateurs
                    </a>
                    <a href="{{ route('web.interventions.index') }}"
                       class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Interventions
                    </a>
                    <a href="{{ route('web.user-competences.index') }}"
                       class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        User-Compétences
                    </a>

                    <!-- Bouton Déconnexion -->
                    <form action="{{ route('web.connexion.destroy', 1) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Message flash de succès -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

</body>
</html>
