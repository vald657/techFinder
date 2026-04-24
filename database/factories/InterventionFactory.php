<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Intervention>
 */
class InterventionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date_int'         => Carbon::now()->subDays($this->faker->numberBetween(0, 365)),
            'note_int'         => $this->faker->numberBetween(0, 20),
            'commentaire_int'  => $this->faker->optional(0.7)->sentence(),
            'code_user_client' => Utilisateur::where('role_user', 'client')->inRandomOrder()->value('code_user'),
            'code_user_techn'  => Utilisateur::where('role_user', 'technicien')->inRandomOrder()->value('code_user'),
            'code_comp'        => Competence::inRandomOrder()->value('code_comp'),
        ];
    }
}
