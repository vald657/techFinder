<?php

namespace Tests\Feature;

use App\Models\Utilisateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilisateurTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Données valides réutilisables
    // -------------------------------------------------------------------------

    private function utilisateurValide(array $overrides = []): array
    {
        return array_merge([
            'nom_user'      => 'Dupont',
            'prenom_user'   => 'Jean',
            'login_user'    => 'jean.dupont',
            'password_user' => 'secret123',
            'tel_user'      => '0600000000',
            'sexe_user'     => 'M',
            'role_user'     => 'client',
            'etat_user'     => 'actif',
        ], $overrides);
    }

    /** Crée un utilisateur et retourne le modèle avec son code_user récupéré depuis la BDD. */
    private function creerUtilisateur(array $overrides = []): Utilisateur
    {
        Utilisateur::create($this->utilisateurValide($overrides));
        return Utilisateur::latest('code_user')->first();
    }

    // -------------------------------------------------------------------------
    // index — GET /api/utilisateurs
    // -------------------------------------------------------------------------

    public function test_index_retourne_liste_vide_quand_aucun_utilisateur(): void
    {
        $response = $this->getJson('/api/utilisateurs');

        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_index_retourne_tous_les_utilisateurs(): void
    {
        $this->creerUtilisateur(['login_user' => 'user1']);
        $this->creerUtilisateur(['login_user' => 'user2']);

        $response = $this->getJson('/api/utilisateurs');

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }

    // -------------------------------------------------------------------------
    // store — POST /api/utilisateurs
    // -------------------------------------------------------------------------

    public function test_store_cree_un_utilisateur_valide(): void
    {
        $payload = $this->utilisateurValide();

        $response = $this->postJson('/api/utilisateurs', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['login_user' => 'jean.dupont']);

        $this->assertDatabaseHas('utilisateur', ['login_user' => 'jean.dupont']);
    }

    public function test_store_echoue_si_nom_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['nom_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nom_user']);
    }

    public function test_store_echoue_si_prenom_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['prenom_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['prenom_user']);
    }

    public function test_store_echoue_si_login_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['login_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login_user']);
    }

    public function test_store_echoue_si_login_deja_utilise(): void
    {
        $this->creerUtilisateur(['login_user' => 'doublons']);

        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['login_user' => 'doublons']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login_user']);
    }

    public function test_store_echoue_si_password_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['password_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password_user']);
    }

    public function test_store_echoue_si_role_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['role_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['role_user']);
    }

    public function test_store_echoue_si_etat_absent(): void
    {
        $response = $this->postJson('/api/utilisateurs', $this->utilisateurValide(['etat_user' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['etat_user']);
    }

    // -------------------------------------------------------------------------
    // show — GET /api/utilisateurs/{id}
    // -------------------------------------------------------------------------

    public function test_show_retourne_un_utilisateur_existant(): void
    {
        $utilisateur = $this->creerUtilisateur();

        $response = $this->getJson("/api/utilisateurs/{$utilisateur->code_user}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['login_user' => $utilisateur->login_user]);
    }

    public function test_show_retourne_erreur_pour_utilisateur_inexistant(): void
    {
        $response = $this->getJson('/api/utilisateurs/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve user']);
    }

    // -------------------------------------------------------------------------
    // update — PUT /api/utilisateurs/{id}
    // -------------------------------------------------------------------------

    public function test_update_modifie_un_utilisateur_existant(): void
    {
        $utilisateur = $this->creerUtilisateur();

        $response = $this->putJson("/api/utilisateurs/{$utilisateur->code_user}", [
            'nom_user'      => 'Martin',
            'prenom_user'   => 'Paul',
            'login_user'    => 'martin.paul',
            'password_user' => 'secret123',
            'tel_user'      => '0601020304',
            'sexe_user'     => 'M',
            'role_user'     => 'technicien',
            'etat_user'     => 'actif',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nom_user' => 'Martin']);

        $this->assertDatabaseHas('utilisateur', [
            'code_user' => $utilisateur->code_user,
            'nom_user'  => 'Martin',
            'role_user' => 'technicien',
        ]);
    }

    public function test_update_retourne_erreur_pour_utilisateur_inexistant(): void
    {
        $response = $this->putJson('/api/utilisateurs/9999', ['nom_user' => 'Ghost']);

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve user']);
    }

    // -------------------------------------------------------------------------
    // destroy — DELETE /api/utilisateurs/{id}
    // -------------------------------------------------------------------------

    public function test_destroy_supprime_un_utilisateur_existant(): void
    {
        $utilisateur = $this->creerUtilisateur();

        $response = $this->deleteJson("/api/utilisateurs/{$utilisateur->code_user}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Utilisateur supprimée avec succès']);

        $this->assertDatabaseMissing('utilisateur', ['code_user' => $utilisateur->code_user]);
    }

    public function test_destroy_retourne_erreur_pour_utilisateur_inexistant(): void
    {
        $response = $this->deleteJson('/api/utilisateurs/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve user']);
    }
}
