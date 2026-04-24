<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Utilisateur;
use App\Models\Competence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * Liste toutes les interventions (paginée).
     */
    public function index(Request $request)
    {
        $query = Intervention::with(['client', 'technicien', 'competence']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('commentaire_int', 'LIKE', "%{$search}%")
                  ->orWhereHas('client', fn ($sq) =>
                      $sq->where('nom_user',    'LIKE', "%{$search}%")
                         ->orWhere('prenom_user', 'LIKE', "%{$search}%"))
                  ->orWhereHas('technicien', fn ($sq) =>
                      $sq->where('nom_user',    'LIKE', "%{$search}%")
                         ->orWhere('prenom_user', 'LIKE', "%{$search}%"))
                  ->orWhereHas('competence', fn ($sq) =>
                      $sq->where('label_comp', 'LIKE', "%{$search}%"));
            });
        }

        match ($request->input('sort', 'recent')) {
            'ancien'    => $query->orderBy('date_int', 'asc'),
            'note_asc'  => $query->orderBy('note_int', 'asc'),
            'note_desc' => $query->orderBy('note_int', 'desc'),
            default     => $query->orderBy('date_int', 'desc'),
        };

        $intervention_list = $query->paginate(10)->appends($request->query());

        return view('interventions.index', compact('intervention_list'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $clients     = Utilisateur::where('role_user', 'client')->get();
        $techniciens = Utilisateur::where('role_user', 'technicien')->get();
        $competences = Competence::all();

        return view('interventions.create', compact('clients', 'techniciens', 'competences'));
    }

    /**
     * Enregistre une nouvelle intervention.
     */
    public function store(Request $request)
    {
        $request->validate([
            'note_int'         => 'required|integer|min:0|max:20',
            'commentaire_int'  => 'nullable|string',
            'code_user_client' => 'required|exists:utilisateur,code_user',
            'code_user_techn'  => 'required|exists:utilisateur,code_user',
            'code_comp'        => 'required|exists:competences,code_comp',
        ]);

        Intervention::create([
            'date_int'         => Carbon::now(),
            'note_int'         => $request->note_int,
            'commentaire_int'  => $request->commentaire_int,
            'code_user_client' => $request->code_user_client,
            'code_user_techn'  => $request->code_user_techn,
            'code_comp'        => $request->code_comp,
        ]);

        return redirect()->route('web.interventions.index')
                         ->with('success', 'Intervention créée avec succès.');
    }

    /**
     * Affiche le détail d'une intervention.
     */
    public function show(string $id)
    {
        $intervention = Intervention::with(['client', 'technicien', 'competence'])
                                    ->findOrFail($id);

        return view('interventions.show', compact('intervention'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id)
    {
        $intervention = Intervention::findOrFail($id);
        $clients      = Utilisateur::where('role_user', 'client')->get();
        $techniciens  = Utilisateur::where('role_user', 'technicien')->get();
        $competences  = Competence::all();

        return view('interventions.edit', compact(
            'intervention', 'clients', 'techniciens', 'competences'
        ));
    }

    /**
     * Met à jour une intervention.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'note_int'         => 'sometimes|integer|min:0|max:20',
            'commentaire_int'  => 'nullable|string',
            'code_user_client' => 'sometimes|exists:utilisateur,code_user',
            'code_user_techn'  => 'sometimes|exists:utilisateur,code_user',
            'code_comp'        => 'sometimes|exists:competences,code_comp',
        ]);

        $intervention = Intervention::findOrFail($id);
        $intervention->update([
            'date_int'         => Carbon::now(),
            'note_int'         => $request->note_int         ?? $intervention->note_int,
            'commentaire_int'  => $request->commentaire_int  ?? $intervention->commentaire_int,
            'code_user_client' => $request->code_user_client ?? $intervention->code_user_client,
            'code_user_techn'  => $request->code_user_techn  ?? $intervention->code_user_techn,
            'code_comp'        => $request->code_comp        ?? $intervention->code_comp,
        ]);

        return redirect()->route('web.interventions.index')
                         ->with('success', 'Intervention mise à jour avec succès.');
    }

    /**
     * Supprime une intervention.
     */
    public function destroy(string $id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        return redirect()->route('web.interventions.index')
                         ->with('success', 'Intervention supprimée avec succès.');
    }
}
