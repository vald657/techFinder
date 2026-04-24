<?php

namespace Database\Factories;

use App\Models\Competence;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCompetence>
 */
class UserCompetenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code_user' => Utilisateur::inRandomOrder()->value('code_user'),
            'code_comp' => Competence::inRandomOrder()->value('code_comp'),
        ];
    }
}
