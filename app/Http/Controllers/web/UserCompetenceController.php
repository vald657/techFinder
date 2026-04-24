<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\UserCompetence;
use App\Models\Utilisateur;
use App\Models\Competence;
use Illuminate\Http\Request;

class UserCompetenceController extends Controller
{
    /**
     * Liste toutes les associations utilisateur–compétence (paginée).
     */
    public function index(Request $request)
    {
        $query = UserCompetence::with(['utilisateur', 'competence']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('utilisateur', fn ($sq) =>
                    $sq->where('nom_user',    'LIKE', "%{$search}%")
                       ->orWhere('prenom_user', 'LIKE', "%{$search}%"))
                  ->orWhereHas('competence', fn ($sq) =>
                    $sq->where('label_comp', 'LIKE', "%{$search}%"));
            });
        }

        match ($request->input('sort', 'recent')) {
            'az_user' => $query->join('utilisateur', 'user_competence.code_user', '=', 'utilisateur.code_user')
                               ->orderBy('utilisateur.nom_user', 'asc')
                               ->select('user_competence.*'),
            'az_comp' => $query->join('competences', 'user_competence.code_comp', '=', 'competences.code_comp')
                               ->orderBy('competences.label_comp', 'asc')
                               ->select('user_competence.*'),
            'ancien'  => $query->orderBy('user_competence.created_at', 'asc'),
            default   => $query->orderBy('user_competence.created_at', 'desc'),
        };

        $usercompetence_list = $query->paginate(10)->appends($request->query());

        return view('usercompetences.index', compact('usercompetence_list'));
    }

    /**
     * Affiche le formulaire d'association.
     */
    public function create()
    {
        $utilisateurs = Utilisateur::all();
        $competences  = Competence::all();

        return view('usercompetences.create', compact('utilisateurs', 'competences'));
    }

    /**
     * Enregistre une nouvelle association.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_user' => 'required|exists:utilisateur,code_user',
            'code_comp' => 'required|exists:competences,code_comp',
        ]);

        UserCompetence::create($request->only('code_user', 'code_comp'));

        return redirect()->route('web.user-competences.index')
                         ->with('success', 'Association créée avec succès.');
    }

    /**
     * Affiche le détail d'une association.
     */
    public function show(string $id)
    {
        // La clé primaire est composée : on attend "code_user_code_comp"
        [$code_user, $code_comp] = explode('_', $id, 2);

        $usercompetence = UserCompetence::where('code_user', $code_user)
                                        ->where('code_comp', $code_comp)
                                        ->firstOrFail();

        return view('usercompetences.show', compact('usercompetence'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id)
    {
        [$code_user, $code_comp] = explode('_', $id, 2);

        $usercompetence = UserCompetence::where('code_user', $code_user)
                                        ->where('code_comp', $code_comp)
                                        ->firstOrFail();

        $utilisateurs = Utilisateur::all();
        $competences  = Competence::all();

        return view('usercompetences.edit', compact('usercompetence', 'utilisateurs', 'competences'));
    }

    /**
     * Met à jour une association.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'code_user' => 'sometimes|exists:utilisateur,code_user',
            'code_comp' => 'sometimes|exists:competences,code_comp',
        ]);

        [$code_user, $code_comp] = explode('_', $id, 2);

        UserCompetence::where('code_user', $code_user)
                      ->where('code_comp', $code_comp)
                      ->update([
                          'code_user' => $request->code_user ?? $code_user,
                          'code_comp' => $request->code_comp ?? $code_comp,
                      ]);

        return redirect()->route('web.user-competences.index')
                         ->with('success', 'Association mise à jour avec succès.');
    }

    /**
     * Supprime une association.
     */
    public function destroy(string $id)
    {
        [$code_user, $code_comp] = explode('_', $id, 2);

        UserCompetence::where('code_user', $code_user)
                      ->where('code_comp', $code_comp)
                      ->delete();

        return redirect()->route('web.user-competences.index')
                         ->with('success', 'Association supprimée avec succès.');
    }
}
