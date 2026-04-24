<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ConnexionController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function index()
    {
        if (Session::has('utilisateur')) {
            return redirect()->route('web.competences.index');
        }

        return view('connexion.index');
    }

    /**
     * Authentifie l'utilisateur et démarre la session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'login_user'    => 'required|string',
            'password_user' => 'required|string',
        ]);

        $utilisateur = Utilisateur::where('login_user', $request->login_user)->first();

        if (!$utilisateur || !Hash::check($request->password_user, $utilisateur->password_user)) {
            return back()->withErrors([
                'login_user' => 'Identifiants incorrects.',
            ])->onlyInput('login_user');
        }

        if ($utilisateur->etat_user !== 'actif') {
            return back()->withErrors([
                'login_user' => 'Ce compte est ' . $utilisateur->etat_user . '. Contactez l\'administrateur.',
            ])->onlyInput('login_user');
        }

        Session::put('utilisateur', $utilisateur);
        Session::put('role', $utilisateur->role_user);

        return redirect()->route('web.competences.index')
                         ->with('success', 'Bienvenue, ' . $utilisateur->prenom_user . ' !');
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy()
    {
        Session::flush();

        return redirect()->route('web.connexion.index')
                         ->with('success', 'Vous êtes déconnecté.');
    }
}
