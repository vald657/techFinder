<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use Illuminate\Http\Request;

class CompetenceController extends Controller
{
    /**
     * Liste toutes les compétences (paginée).
     */
    public function index(Request $request)
    {
        $query = Competence::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('label_comp', 'LIKE', "%{$search}%")
                  ->orWhere('description_comp', 'LIKE', "%{$search}%");
            });
        }

        match ($request->input('sort', 'recent')) {
            'az'     => $query->orderBy('label_comp', 'asc'),
            'za'     => $query->orderBy('label_comp', 'desc'),
            'ancien' => $query->orderBy('created_at', 'asc'),
            default  => $query->orderBy('created_at', 'desc'),
        };

        $competence_list = $query->paginate(10)->appends($request->query());

        return view('competences.index', compact('competence_list'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('competences.create');
    }

    /**
     * Enregistre une nouvelle compétence.
     */
    public function store(Request $request)
    {
        $request->validate([
            'label_comp'       => 'required|string|max:255',
            'description_comp' => 'nullable|string',
        ]);

        Competence::create($request->only('label_comp', 'description_comp'));

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence créée avec succès.');
    }

    /**
     * Affiche le détail d'une compétence.
     */
    public function show(string $id)
    {
        $competence = Competence::findOrFail($id);

        return view('competences.show', compact('competence'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id)
    {
        $competence = Competence::findOrFail($id);

        return view('competences.edit', compact('competence'));
    }

    /**
     * Met à jour une compétence.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'label_comp'       => 'required|string|max:255',
            'description_comp' => 'nullable|string',
        ]);

        $competence = Competence::findOrFail($id);
        $competence->update($request->only('label_comp', 'description_comp'));

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence mise à jour avec succès.');
    }

    /**
     * Supprime une compétence.
     */
    public function destroy(string $id)
    {
        $competence = Competence::findOrFail($id);
        $competence->delete();

        return redirect()->route('web.competences.index')
                         ->with('success', 'Compétence supprimée avec succès.');
    }
}
