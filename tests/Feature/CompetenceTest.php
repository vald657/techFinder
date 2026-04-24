<?php

namespace Tests\Feature;

use App\Models\Competence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetenceTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // index — GET /api/competences
    // -------------------------------------------------------------------------

    public function test_index_retourne_liste_vide_quand_aucune_competence(): void
    {
        $response = $this->getJson('/api/competences');

        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_index_retourne_toutes_les_competences(): void
    {
        Competence::create(['label_comp' => 'PHP', 'description_comp' => 'Langage backend']);
        Competence::create(['label_comp' => 'Vue.js', 'description_comp' => null]);

        $response = $this->getJson('/api/competences');

        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonFragment(['label_comp' => 'PHP'])
                 ->assertJsonFragment(['label_comp' => 'Vue.js']);
    }

    // -------------------------------------------------------------------------
    // store — POST /api/competences
    // -------------------------------------------------------------------------

    public function test_store_cree_une_competence_valide(): void
    {
        $payload = [
            'label_comp'       => 'Laravel',
            'description_comp' => 'Framework PHP',
        ];

        $response = $this->postJson('/api/competences', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['label_comp' => 'Laravel']);

        $this->assertDatabaseHas('competences', ['label_comp' => 'Laravel']);
    }

    public function test_store_cree_une_competence_sans_description(): void
    {
        $response = $this->postJson('/api/competences', ['label_comp' => 'Docker']);

        $response->assertStatus(201)
                 ->assertJsonFragment(['label_comp' => 'Docker']);

        $this->assertDatabaseHas('competences', ['label_comp' => 'Docker']);
    }

    public function test_store_echoue_si_label_absent(): void
    {
        $response = $this->postJson('/api/competences', ['description_comp' => 'Sans label']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['label_comp']);
    }

    public function test_store_echoue_si_label_trop_long(): void
    {
        $response = $this->postJson('/api/competences', [
            'label_comp' => str_repeat('a', 256),
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['label_comp']);
    }

    // -------------------------------------------------------------------------
    // show — GET /api/competences/{id}
    // -------------------------------------------------------------------------

    public function test_show_retourne_une_competence_existante(): void
    {
        $competence = Competence::create([
            'label_comp'       => 'MySQL',
            'description_comp' => 'Base de données relationnelle',
        ]);

        $response = $this->getJson("/api/competences/{$competence->code_comp}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['label_comp' => 'MySQL']);
    }

    public function test_show_retourne_erreur_pour_competence_inexistante(): void
    {
        $response = $this->getJson('/api/competences/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve competence']);
    }

    // -------------------------------------------------------------------------
    // update — PUT /api/competences/{id}
    // -------------------------------------------------------------------------

    public function test_update_modifie_une_competence_existante(): void
    {
        $competence = Competence::create([
            'label_comp'       => 'Symfony',
            'description_comp' => 'Ancien desc',
        ]);

        $response = $this->putJson("/api/competences/{$competence->code_comp}", [
            'label_comp'       => 'Symfony 7',
            'description_comp' => 'Nouveau desc',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['label_comp' => 'Symfony 7']);

        $this->assertDatabaseHas('competences', [
            'code_comp'        => $competence->code_comp,
            'label_comp'       => 'Symfony 7',
            'description_comp' => 'Nouveau desc',
        ]);
    }

    public function test_update_echoue_si_label_absent(): void
    {
        $competence = Competence::create(['label_comp' => 'React']);

        $response = $this->putJson("/api/competences/{$competence->code_comp}", [
            'description_comp' => 'Sans label',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['label_comp']);
    }

    public function test_update_retourne_erreur_pour_competence_inexistante(): void
    {
        $response = $this->putJson('/api/competences/9999', [
            'label_comp' => 'Inexistant',
        ]);

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve competence']);
    }

    // -------------------------------------------------------------------------
    // destroy — DELETE /api/competences/{id}
    // -------------------------------------------------------------------------

    public function test_destroy_supprime_une_competence_existante(): void
    {
        $competence = Competence::create(['label_comp' => 'Angular']);

        $response = $this->deleteJson("/api/competences/{$competence->code_comp}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Compétence supprimée avec succès']);

        $this->assertDatabaseMissing('competences', ['code_comp' => $competence->code_comp]);
    }

    public function test_destroy_retourne_erreur_pour_competence_inexistante(): void
    {
        $response = $this->deleteJson('/api/competences/9999');

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Failed to retrieve competence']);
    }

    // -------------------------------------------------------------------------
    // search — GET /api/competences/search?keyword=
    // -------------------------------------------------------------------------

    public function test_search_retourne_resultats_sur_label(): void
    {
        Competence::create(['label_comp' => 'JavaScript', 'description_comp' => 'Langage web']);
        Competence::create(['label_comp' => 'Python',     'description_comp' => 'Data science']);

        $response = $this->getJson('/api/competences/search?keyword=Java');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['label_comp' => 'JavaScript']);
    }

    public function test_search_retourne_resultats_sur_description(): void
    {
        Competence::create(['label_comp' => 'TypeScript', 'description_comp' => 'Superset de JavaScript']);
        Competence::create(['label_comp' => 'Go',         'description_comp' => 'Langage compilé']);

        $response = $this->getJson('/api/competences/search?keyword=compilé');

        $response->assertStatus(200)
                 ->assertJsonCount(1)
                 ->assertJsonFragment(['label_comp' => 'Go']);
    }

    public function test_search_retourne_liste_vide_si_aucun_resultat(): void
    {
        Competence::create(['label_comp' => 'Rust']);

        $response = $this->getJson('/api/competences/search?keyword=xyz_inexistant');

        $response->assertStatus(200)
                 ->assertJsonCount(0);
    }

    public function test_search_echoue_si_keyword_absent(): void
    {
        $response = $this->getJson('/api/competences/search');

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['keyword']);
    }
}
