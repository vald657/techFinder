<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\UserCompetenceController;


Route::get('competences/search', [CompetenceController::class, 'search']);
Route::apiResource('competences', CompetenceController::class);
Route::apiResource('interventions', InterventionController::class);
Route::apiResource('utilisateurs', UtilisateurController::class);
Route::apiResource('user-competences', UserCompetenceController::class);    
Route::delete('/user-competences/{code_user}/{code_comp}', [UserCompetenceController::class, 'destroy']);
Route::get('user-competences/{code_user}/{code_comp}', [UserCompetenceController::class, 'show']);
Route::put('user-competences/{code_user}/{code_comp}', [UserCompetenceController::class, 'update']);
