<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $interventions = Intervention::all();
            return response()->json($interventions, 200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve intervention', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'note_int' => 'required|integer|min:0|max:20',
            'commentaire_int' => 'nullable|string',
            'code_user_client' => 'required|string',
            'code_user_techn' => 'required|string',
            'code_comp' => 'required|integer'
        ]);

        try{
            $intervention = Intervention::create([
                'date_int' => Carbon::now(),
                'note_int' => $request->note_int,
                'commentaire_int' => $request->commentaire_int,
                'code_user_client' => $request->code_user_client,
                'code_user_techn' => $request->code_user_techn,
                'code_comp' =>  $request->code_comp
            ]);

            return response()->json($intervention, 201);
        } catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve intervention', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $code_int)
    {
        try{
            $intervention = Intervention::findOrFail($code_int);
            return response()->json($intervention, 200);
        }catch (\Exception $e){
            return response()->json(['error' => 'Failed to retrieve intervention', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $code_int)
    {
        $request->validate([
            'date_int' => 'sometimes|date',
            'note_int' => 'nullable|integer|min:0|max:20',
            'commentaire_int' => 'nullable|string',
            'code_user_client' => 'sometimes|string',
            'code_user_techn' => 'sometimes|string',
            'code_comp' => 'sometimes|integer'
        ]);

        try{
            $intervention = Intervention::findOrFail($code_int);
            $intervention->update(([
                'date_int' => Carbon::now(),
                'note_int' => $request->note_int,
                'commentaire_int' => $request->commentaire_int,
                'code_user_client' => $request->code_user_client,
                'code_user_techn' => $request->code_user_techn,
                'code_comp' =>  $request->code_comp
            ]));
            return response()->json($intervention, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve intervention', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $code_int)
    {
        try{
            $intervention = Intervention::findOrFail($code_int);
            $intervention->delete();

            return response()->json(['message' => 'Intervention supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve intervention', 'message' => $e->getMessage()], 500);
        }
    }
}
