<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\UserCompetence;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class UserCompetenceSeeder extends Seeder
{
    public function run(): void
    {
        $utilisateurs = Utilisateur::pluck('code_user');
        $competences  = Competence::pluck('code_comp');

        if ($utilisateurs->isEmpty() || $competences->isEmpty()) {
            $this->command->warn('UserCompetenceSeeder ignoré : utilisateurs ou compétences manquants.');
            return;
        }

        $paires = [];

        // Chaque utilisateur reçoit entre 1 et 4 compétences aléatoires uniques
        foreach ($utilisateurs as $codeUser) {
            $nbComp = rand(1, min(4, $competences->count()));
            $compsChoisies = $competences->shuffle()->take($nbComp);

            foreach ($compsChoisies as $codeComp) {
                $cle = "{$codeUser}_{$codeComp}";
                if (!isset($paires[$cle])) {
                    UserCompetence::create([
                        'code_user' => $codeUser,
                        'code_comp' => $codeComp,
                    ]);
                    $paires[$cle] = true;
                }
            }
        }
    }
}
