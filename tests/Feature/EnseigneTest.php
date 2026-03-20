<?php

namespace Tests\Feature;

use App\Models\Ec;
use App\Models\Enseigne;
use App\Models\Personnel;
use Tests\TestCase;

class EnseigneTest extends TestCase
{
    public function test_get_enseignes()
    {
        $response = $this->getJson('/api/enseignes');
        $response->assertStatus(200);
    }

    public function test_create_enseigne()
    {
        // On récupère des IDs existants
        $persId = Personnel::inRandomOrder()->first()->code_pers;
        $ecId = Ec::inRandomOrder()->first()->code_ec;

        // On vérifie si ça existe déjà pour éviter le doublon
        if (Enseigne::where('code_pers', $persId)->where('code_ec', $ecId)->exists()) {
            // Si existe, on supprime d'abord pour le test
            Enseigne::where('code_pers', $persId)->where('code_ec', $ecId)->delete();
        }

        $data = [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'nbh_heure' => 10,
            'heure_debut' => '2025-01-01',
            'heure_fin' => '2025-06-01',
            'statut' => 'actif',
        ];

        $response = $this->postJson('/api/enseignes', $data);

        $response->assertStatus(201); // Ou 200

        // Nettoyage
        Enseigne::where('code_pers', $persId)->where('code_ec', $ecId)->delete();
    }

    public function test_update_enseigne()
    {
        $enseigne = Enseigne::inRandomOrder()->first();

        if (! $enseigne) {
            $persId = Personnel::inRandomOrder()->first()->code_pers;
            $ecId = Ec::inRandomOrder()->first()->code_ec;
            Enseigne::where(['code_pers' => $persId, 'code_ec' => $ecId])->delete();
            $enseigne = Enseigne::create(['code_pers' => $persId, 'code_ec' => $ecId, 'nbh_heure' => 10, 'statut' => 'actif']);
        }

        $persId = $enseigne->code_pers;
        $ecId = $enseigne->code_ec;

        $updateData = [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'nbh_heure' => 50,
            'statut' => 'terminé',
        ];

        $response = $this->putJson('/api/enseignes/update', $updateData);

        $response->assertStatus(200);

        // Pas de nettoyage nécessaire car on modifie une donnée existante valide, ou on laisse tel quel.
    }

    public function test_delete_enseigne()
    {
        $enseigne = Enseigne::inRandomOrder()->first();

        if (! $enseigne) {
            $persId = Personnel::inRandomOrder()->first()->code_pers;
            $ecId = Ec::inRandomOrder()->first()->code_ec;
            Enseigne::where(['code_pers' => $persId, 'code_ec' => $ecId])->delete();
            $enseigne = Enseigne::create(['code_pers' => $persId, 'code_ec' => $ecId, 'statut' => 'to_delete']);
        }

        $persId = $enseigne->code_pers;
        $ecId = $enseigne->code_ec;

        // Query string
        $url = "/api/enseignes/delete?code_pers={$persId}&code_ec={$ecId}";

        $response = $this->deleteJson($url);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('enseigne', ['code_pers' => $persId, 'code_ec' => $ecId]);
    }
}
