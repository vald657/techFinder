<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Intervention;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InterventionSeeder extends Seeder
{
    public function run(): void
    {
        $clients     = Utilisateur::where('role_user', 'client')->pluck('code_user');
        $techniciens = Utilisateur::where('role_user', 'technicien')->pluck('code_user');
        $competences = Competence::pluck('code_comp');

        if ($clients->isEmpty() || $techniciens->isEmpty() || $competences->isEmpty()) {
            $this->command->warn('InterventionSeeder ignoré : clients, techniciens ou compétences manquants.');
            return;
        }

        for ($i = 0; $i < 80; $i++) {
            Intervention::create([
                'date_int'         => Carbon::now()->subDays(rand(0, 365)),
                'note_int'         => rand(0, 20),
                'commentaire_int'  => rand(0, 1) ? fake()->sentence() : null,
                'code_user_client' => $clients->random(),
                'code_user_techn'  => $techniciens->random(),
                'code_comp'        => $competences->random(),
            ]);
        }
    }
}
