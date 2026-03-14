<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $utilisateurs = Utilisateur::all();
            return response()->json($utilisateurs,200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_user' => 'required|string',
            'prenom_user' => 'required|string',
            'login_user' => 'required|string|unique:utilisateur,login_user',
            'password_user' => 'required|string',
            'tel_user' => 'nullable|string',
            'sexe_user' => 'nullable|string',
            'role_user' => 'required|string',
            'etat_user' => 'required|string'
        ]);

        try{
            $utilisateur = Utilisateur::create([
                'nom_user' => $request->nom_user,
                'prenom_user' => $request->prenom_user,
                'login_user' => $request->login_user,
                'password_user' => $request->password_user,
                'tel_user' => $request->tel_user,
                'sexe_user' => $request->sexe_user,
                'role_user' => $request->role_user,
                'etat_user' => $request->etat_user
            ]);
            return response()->json($utilisateur, 201);     
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $code_user)
    {
        try{
            $utilisateur = Utilisateur::findOrFail($code_user);
            return response()->json($utilisateur,200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $code_user)
    {
        $request->validate([
            'nom_user' => 'sometimes|string',
            'prenom_user' => 'sometimes|string',
            'login_user' => 'sometimes|string',
            'password_user' => 'sometimes|string',
            'tel_user' => 'nullable|string',
            'sexe_user' => 'nullable|string',
            'role_user' => 'sometimes|string',
            'etat_user' => 'sometimes|string'
        ]);

        try{
            $utilisateur = Utilisateur::findOrFail($code_user);
            $utilisateur->update(([
                'nom_user' => $request->nom_user,
                'prenom_user' => $request->prenom_user,
                'login_user' => $request->login_user,
                'password_user' => $request->password_user,
                'tel_user' => $request->tel_user,
                'sexe_user' => $request->sexe_user,
                'role_user' => $request->role_user,
                'etat_user' => $request->etat_user
            ]));
            return response()->json($utilisateur,200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $code_user)
    {
        try{
            $utilisateur = Utilisateur::findOrFail($code_user);
            $utilisateur->delete();

            return response()->json(['message' => 'Utilisateur supprimée avec succès'], 200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }
    }
}
