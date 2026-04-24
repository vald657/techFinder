<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    /**
     * Liste tous les utilisateurs (paginée).
     */
    public function index(Request $request)
    {
        $query = Utilisateur::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_user',    'LIKE', "%{$search}%")
                  ->orWhere('prenom_user', 'LIKE', "%{$search}%")
                  ->orWhere('login_user',  'LIKE', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role_user', $role);
        }

        if ($etat = $request->input('etat')) {
            $query->where('etat_user', $etat);
        }

        match ($request->input('sort', 'recent')) {
            'az'     => $query->orderBy('nom_user', 'asc'),
            'za'     => $query->orderBy('nom_user', 'desc'),
            'ancien' => $query->orderBy('created_at', 'asc'),
            default  => $query->orderBy('created_at', 'desc'),
        };

        $utilisateur_list = $query->paginate(10)->appends($request->query());

        return view('utilisateurs.index', compact('utilisateur_list'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('utilisateurs.create');
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_user'      => 'required|string|max:255',
            'prenom_user'   => 'required|string|max:255',
            'login_user'    => 'required|string|unique:utilisateur,login_user',
            'password_user' => 'required|string|min:6|confirmed',
            'tel_user'      => 'required|string|max:20',
            'sexe_user'     => 'required|in:M,F',
            'role_user'     => 'required|in:admin,technicien,client',
            'etat_user'     => 'required|in:actif,inactif,bloquer',
        ]);

        Utilisateur::create(array_merge(
            $request->only(['nom_user', 'prenom_user', 'login_user', 'tel_user', 'sexe_user', 'role_user', 'etat_user']),
            ['password_user' => Hash::make($request->password_user)]
        ));

        return redirect()->route('web.utilisateurs.index')
                         ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche le détail d'un utilisateur.
     */
    public function show(string $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        return view('utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        return view('utilisateurs.edit', compact('utilisateur'));
    }

    /**
     * Met à jour un utilisateur.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom_user'      => 'sometimes|string|max:255',
            'prenom_user'   => 'sometimes|string|max:255',
            'login_user'    => 'sometimes|string|unique:utilisateur,login_user,' . $id . ',code_user',
            'password_user' => 'sometimes|string|min:6|confirmed',
            'tel_user'      => 'nullable|string|max:20',
            'sexe_user'     => 'nullable|in:M,F',
            'role_user'     => 'sometimes|in:admin,technicien,client',
            'etat_user'     => 'sometimes|in:actif,inactif,bloquer',
        ]);

        $utilisateur = Utilisateur::findOrFail($id);

        $data = $request->only(['nom_user', 'prenom_user', 'login_user', 'tel_user', 'sexe_user', 'role_user', 'etat_user']);

        if ($request->filled('password_user')) {
            $data['password_user'] = Hash::make($request->password_user);
        }

        $utilisateur->update($data);

        return redirect()->route('web.utilisateurs.index')
                         ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(string $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        return redirect()->route('web.utilisateurs.index')
                         ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
