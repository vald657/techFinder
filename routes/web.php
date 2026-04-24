<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\CompetenceController;
use App\Http\Controllers\web\UtilisateurController;
use App\Http\Controllers\web\InterventionController;
use App\Http\Controllers\web\UserCompetenceController;
use App\Http\Controllers\web\ConnexionController;

Route::get('/', function () {
    return redirect()->route('web.connexion.index');
});

// Connexion / Déconnexion
Route::get('/connexion',   [ConnexionController::class, 'index'])->name('web.connexion.index');
Route::post('/connexion',  [ConnexionController::class, 'store'])->name('web.connexion.store');
Route::delete('/deconnexion', [ConnexionController::class, 'destroy'])->name('web.connexion.destroy');

// Ressources protégées
Route::prefix('')->name('web.')->group(function () {
    Route::resource('competences',    CompetenceController::class);
    Route::resource('utilisateurs',   UtilisateurController::class);
    Route::resource('interventions',  InterventionController::class);
    Route::resource('user-competences', UserCompetenceController::class);
});
