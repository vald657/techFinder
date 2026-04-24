<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes FK pour pouvoir tronquer
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('intervention')->truncate();
        DB::table('user_competence')->truncate();
        DB::table('utilisateur')->truncate();
        DB::table('competences')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Compte admin garanti
        Utilisateur::create([
            'nom_user'      => 'Admin',
            'prenom_user'   => 'Super',
            'login_user'    => 'admin',
            'password_user' => Hash::make('password'),
            'tel_user'      => '0600000000',
            'sexe_user'     => 'M',
            'role_user'     => 'admin',
            'etat_user'     => 'actif',
        ]);

        // Données de base — ordre important (FK)
        $this->call([
            CompetenceSeeder::class,     // 100 compétences
            UtilisateurSeeder::class,    // 50 utilisateurs (clients + techniciens)
            UserCompetenceSeeder::class, // associations utilisateur–compétence
            InterventionSeeder::class,   // 80 interventions
        ]);
    }
}
