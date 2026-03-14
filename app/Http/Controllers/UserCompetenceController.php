<?php

namespace App\Http\Controllers;

use App\Models\UserCompetence;
use Illuminate\Http\Request;

class UserCompetenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $usercomp = UserCompetence::all();
            return response()->json($usercomp, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve userCompetence', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_user' => 'required|exists:utilisateur,code_user',
            'code_comp' => 'required|exists:competences,code_comp'
        ]);

        try {
            $usercomp = UserCompetence::create([
                'code_user' => $request->code_user,
                'code_comp' => $request->code_comp
            ]);

            return response()->json($usercomp, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create userCompetence', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($code_user, $code_comp)
    {
    try {
            $userCompetence = UserCompetence::where('code_user', $code_user)
                ->where('code_comp', $code_comp)
                ->firstOrFail();

            return response()->json($userCompetence, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve userCompetence',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_user, $code_comp)
    {
        $request->validate([
            'code_user' => 'sometimes|integer',
            'code_comp' => 'sometimes|integer'
        ]);

        try {

            $updated = UserCompetence::where('code_user', $code_user)
                ->where('code_comp', $code_comp)
                ->update([
                    'code_user' => $request->code_user ?? $code_user,
                    'code_comp' => $request->code_comp ?? $code_comp
                ]);

            return response()->json([
                "message" => "UserCompetence mise à jour",
                "updated_rows" => $updated
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Failed to update userCompetence',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_user, $code_comp)
{
    try {

        $deleted = UserCompetence::where('code_user', $code_user)
            ->where('code_comp', $code_comp)
            ->delete();

        return response()->json([
            "message" => "User competence supprimée",
            "deleted_rows" => $deleted
        ]);

    } catch (\Exception $e) {

        return response()->json([
            "error" => "Failed to delete userCompetence",
            "message" => $e->getMessage()
        ], 500);
    }
}

}

