<?php

namespace Tests\Feature;

use App\Models\Competence;
use App\Models\Utilisateur;
use App\Models\UserCompetence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCompetenceTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function creerUtilisateur(string $login): Utilisateur
    {
        Utilisateur::create([
            'nom_user'      => 'Test',
            'prenom_user'   => 'User',
            'login_user'    => $login,
            'password_user' => 'secret',
            'tel_user'      => '0600000000',
            'sexe_user'     => 'M',
            'role_user'     => 'client',
            'etat_user'     => 'actif',
        ]);
        return Utilisateur::where('login_user', $login)->first();
    }

    private function creerCompetence(string $label): Competence
    {
        return Competence::create(['label_comp' => $label]);
    }

    private function creerUserCompetence(int $codeUser, int $codeComp): UserCompetence
    {
        return UserCompetence::create([
            'code_user' => $codeUser,
            'code_comp' => $codeComp,
        ]);
    }

    // -------------------------------------------------------------------------
    // index — GET /api/user-competences
    // -------------------------------------------------------------------------

    public function test_index_retourne_liste_vide_quand_aucune_association(): void
    {
        $response = $this->getJson('/api/user-competences');

        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_index_retourne_toutes_les_associations(): void
    {
        $user1 = $this->creerUtilisateur('user1');
        $user2 = $this->creerUtilisateur('user2');
        $comp1 = $this->creerCompetence('PHP');
        $comp2 = $this->creerCompetence('Python');

        $this->creerUserCompetence($user1->code_user, $comp1->code_comp);
        $this->creerUserCompetence($user2->code_user, $comp2->code_comp);

        $response = $this->getJson('/api/user-competences');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    // -------------------------------------------------------------------------
    // store — POST /api/user-competences
    // -------------------------------------------------------------------------

    public function test_store_cree_une_association_valide(): void
    {
        $user = $this->creerUtilisateur('user3');
        $comp = $this->creerCompetence('Laravel');

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $user->code_user,
            'code_comp' => $comp->code_comp,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'code_user' => $user->code_user,
                     'code_comp' => $comp->code_comp,
                 ]);

        $this->assertDatabaseHas('user_competence', [
            'code_user' => $user->code_user,
            'code_comp' => $comp->code_comp,
        ]);
    }

    public function test_store_echoue_si_code_user_absent(): void
    {
        $comp = $this->creerCompetence('Docker');

        $response = $this->postJson('/api/user-competences', [
            'code_comp' => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_user']);
    }

    public function test_store_echoue_si_code_comp_absent(): void
    {
        $user = $this->creerUtilisateur('user4');

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $user->code_user,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_comp']);
    }

    public function test_store_echoue_si_utilisateur_inexistant(): void
    {
        $comp = $this->creerCompetence('MySQL');

        $response = $this->postJson('/api/user-competences', [
            'code_user' => 9999,
            'code_comp' => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_user']);
    }

    public function test_store_echoue_si_competence_inexistante(): void
    {
        $user = $this->creerUtilisateur('user5');

        $response = $this->postJson('/api/user-competences', [
            'code_user' => $user->code_user,
            'code_comp' => 9999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_comp']);
    }

    // -------------------------------------------------------------------------
    // show — GET /api/user-competences/{code_user}/{code_comp}
    // -------------------------------------------------------------------------

    public function test_show_retourne_une_association_existante(): void
    {
        $user = $this->creerUtilisateur('user6');
        $comp = $this->creerCompetence('Vue.js');
        $this->creerUserCompetence($user->code_user, $comp->code_comp);

        $response = $this->getJson("/api/user-competences/{$user->code_user}/{$comp->code_comp}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'code_user' => $user->code_user,
                     'code_comp' => $comp->code_comp,
                 ]);
    }

    public function test_show_retourne_erreur_si_association_inexistante(): void
    {
        $response = $this->getJson('/api/user-competences/9999/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve userCompetence']);
    }

    // -------------------------------------------------------------------------
    // update — PUT /api/user-competences/{code_user}/{code_comp}
    // -------------------------------------------------------------------------

    public function test_update_modifie_une_association_existante(): void
    {
        $user  = $this->creerUtilisateur('user7');
        $comp1 = $this->creerCompetence('TypeScript');
        $comp2 = $this->creerCompetence('Rust');
        $this->creerUserCompetence($user->code_user, $comp1->code_comp);

        $response = $this->putJson("/api/user-competences/{$user->code_user}/{$comp1->code_comp}", [
            'code_user' => $user->code_user,
            'code_comp' => $comp2->code_comp,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'UserCompetence mise à jour']);
    }

    public function test_update_retourne_zero_lignes_si_association_inexistante(): void
    {
        $response = $this->putJson('/api/user-competences/9999/9999', [
            'code_user' => 9999,
            'code_comp' => 9999,
        ]);

        // Aucune ligne n'est trouvée mais aucune exception n'est levée
        $response->assertStatus(200)
                 ->assertJsonFragment(['updated_rows' => 0]);
    }

    // -------------------------------------------------------------------------
    // destroy — DELETE /api/user-competences/{code_user}/{code_comp}
    // -------------------------------------------------------------------------

    public function test_destroy_supprime_une_association_existante(): void
    {
        $user = $this->creerUtilisateur('user8');
        $comp = $this->creerCompetence('Go');
        $this->creerUserCompetence($user->code_user, $comp->code_comp);

        $response = $this->deleteJson("/api/user-competences/{$user->code_user}/{$comp->code_comp}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'User competence supprimée']);

        $this->assertDatabaseMissing('user_competence', [
            'code_user' => $user->code_user,
            'code_comp' => $comp->code_comp,
        ]);
    }

    public function test_destroy_retourne_zero_lignes_si_association_inexistante(): void
    {
        $response = $this->deleteJson('/api/user-competences/9999/9999');

        // Aucune ligne trouvée mais pas d'exception
        $response->assertStatus(200)
                 ->assertJsonFragment(['deleted_rows' => 0]);
    }
}
