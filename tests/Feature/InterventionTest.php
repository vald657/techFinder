<?php

namespace Tests\Feature;

use App\Models\Competence;
use App\Models\Intervention;
use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterventionTest extends TestCase
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

    private function creerCompetence(string $label = 'PHP'): Competence
    {
        return Competence::create(['label_comp' => $label]);
    }

    private function creerIntervention(int $codeClient, int $codeTechn, int $codeComp): Intervention
    {
        Intervention::create([
            'note_int'        => 15,
            'commentaire_int' => 'Bon travail',
            'code_user_client' => $codeClient,
            'code_user_techn'  => $codeTechn,
            'code_comp'        => $codeComp,
        ]);
        return Intervention::latest('code_int')->first();
    }

    // -------------------------------------------------------------------------
    // index — GET /api/interventions
    // -------------------------------------------------------------------------

    public function test_index_retourne_liste_vide_quand_aucune_intervention(): void
    {
        $response = $this->getJson('/api/interventions');

        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_index_retourne_toutes_les_interventions(): void
    {
        $client  = $this->creerUtilisateur('client1');
        $techn   = $this->creerUtilisateur('techn1');
        $comp    = $this->creerCompetence();

        $this->creerIntervention($client->code_user, $techn->code_user, $comp->code_comp);
        $this->creerIntervention($client->code_user, $techn->code_user, $comp->code_comp);

        $response = $this->getJson('/api/interventions');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    // -------------------------------------------------------------------------
    // store — POST /api/interventions
    // -------------------------------------------------------------------------

    public function test_store_cree_une_intervention_valide(): void
    {
        $client = $this->creerUtilisateur('client2');
        $techn  = $this->creerUtilisateur('techn2');
        $comp   = $this->creerCompetence('Laravel');

        $response = $this->postJson('/api/interventions', [
            'note_int'         => 18,
            'commentaire_int'  => 'Excellent',
            'code_user_client' => (string) $client->code_user,
            'code_user_techn'  => (string) $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['note_int' => 18]);

        $this->assertDatabaseHas('intervention', ['note_int' => 18]);
    }

    public function test_store_cree_intervention_sans_commentaire(): void
    {
        $client = $this->creerUtilisateur('client3');
        $techn  = $this->creerUtilisateur('techn3');
        $comp   = $this->creerCompetence('Docker');

        $response = $this->postJson('/api/interventions', [
            'note_int'         => 10,
            'code_user_client' => (string) $client->code_user,
            'code_user_techn'  => (string) $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(201);
    }

    public function test_store_echoue_si_note_absente(): void
    {
        $client = $this->creerUtilisateur('client4');
        $techn  = $this->creerUtilisateur('techn4');
        $comp   = $this->creerCompetence('MySQL');

        $response = $this->postJson('/api/interventions', [
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['note_int']);
    }

    public function test_store_echoue_si_note_superieure_a_20(): void
    {
        $client = $this->creerUtilisateur('client5');
        $techn  = $this->creerUtilisateur('techn5');
        $comp   = $this->creerCompetence('Redis');

        $response = $this->postJson('/api/interventions', [
            'note_int'         => 25,
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['note_int']);
    }

    public function test_store_echoue_si_note_negative(): void
    {
        $client = $this->creerUtilisateur('client6');
        $techn  = $this->creerUtilisateur('techn6');
        $comp   = $this->creerCompetence('Nginx');

        $response = $this->postJson('/api/interventions', [
            'note_int'         => -1,
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['note_int']);
    }

    public function test_store_echoue_si_code_user_client_absent(): void
    {
        $techn = $this->creerUtilisateur('techn7');
        $comp  = $this->creerCompetence('Git');

        $response = $this->postJson('/api/interventions', [
            'note_int'        => 12,
            'code_user_techn' => $techn->code_user,
            'code_comp'       => $comp->code_comp,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_user_client']);
    }

    public function test_store_echoue_si_code_comp_absent(): void
    {
        $client = $this->creerUtilisateur('client8');
        $techn  = $this->creerUtilisateur('techn8');

        $response = $this->postJson('/api/interventions', [
            'note_int'         => 12,
            'code_user_client' => $client->code_user,
            'code_user_techn'  => $techn->code_user,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_comp']);
    }

    // -------------------------------------------------------------------------
    // show — GET /api/interventions/{id}
    // -------------------------------------------------------------------------

    public function test_show_retourne_une_intervention_existante(): void
    {
        $client      = $this->creerUtilisateur('client9');
        $techn       = $this->creerUtilisateur('techn9');
        $comp        = $this->creerCompetence('Vue');
        $intervention = $this->creerIntervention($client->code_user, $techn->code_user, $comp->code_comp);

        $response = $this->getJson("/api/interventions/{$intervention->code_int}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['code_int' => $intervention->code_int]);
    }

    public function test_show_retourne_erreur_pour_intervention_inexistante(): void
    {
        $response = $this->getJson('/api/interventions/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve intervention']);
    }

    // -------------------------------------------------------------------------
    // update — PUT /api/interventions/{id}
    // -------------------------------------------------------------------------

    public function test_update_modifie_une_intervention_existante(): void
    {
        $client      = $this->creerUtilisateur('client10');
        $techn       = $this->creerUtilisateur('techn10');
        $comp        = $this->creerCompetence('React');
        $intervention = $this->creerIntervention($client->code_user, $techn->code_user, $comp->code_comp);

        $response = $this->putJson("/api/interventions/{$intervention->code_int}", [
            'note_int'         => 20,
            'commentaire_int'  => 'Parfait',
            'code_user_client' => (string) $client->code_user,
            'code_user_techn'  => (string) $techn->code_user,
            'code_comp'        => $comp->code_comp,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('intervention', [
            'code_int' => $intervention->code_int,
            'note_int' => 20,
        ]);
    }

    public function test_update_retourne_erreur_pour_intervention_inexistante(): void
    {
        $response = $this->putJson('/api/interventions/9999', ['note_int' => 10]);

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve intervention']);
    }

    // -------------------------------------------------------------------------
    // destroy — DELETE /api/interventions/{id}
    // -------------------------------------------------------------------------

    public function test_destroy_supprime_une_intervention_existante(): void
    {
        $client      = $this->creerUtilisateur('client11');
        $techn       = $this->creerUtilisateur('techn11');
        $comp        = $this->creerCompetence('Angular');
        $intervention = $this->creerIntervention($client->code_user, $techn->code_user, $comp->code_comp);

        $response = $this->deleteJson("/api/interventions/{$intervention->code_int}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Intervention supprimée avec succès']);

        $this->assertDatabaseMissing('intervention', ['code_int' => $intervention->code_int]);
    }

    public function test_destroy_retourne_erreur_pour_intervention_inexistante(): void
    {
        $response = $this->deleteJson('/api/interventions/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve intervention']);
    }
}
