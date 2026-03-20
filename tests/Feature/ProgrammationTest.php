<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Programmation;
use App\Models\Personnel;
use App\Models\Ec;
use App\Models\Salle;
use Illuminate\Support\Facades\DB;

class ProgrammationTest extends TestCase
{

    public function test_get_programmations()
    {
        $response = $this->getJson('/api/programmations');
        $response->assertStatus(200);
    }

    public function test_create_programmation()
    {
        $persId = Personnel::inRandomOrder()->first()->code_pers;
        $ecId = Ec::inRandomOrder()->first()->code_ec;
        $salleId = Salle::inRandomOrder()->first()->num_sale;


        Programmation::where([
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId
        ])->delete();

        $data = [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId,
            'date' => '2099-12-01',
            'heure_debut' => '06:00',
            'heure_fin' => '07:00',
            'nbre_heure' => 1,
            'status' => 'planifié'
        ];

        $response = $this->postJson('/api/programmations', $data);

        $response->assertStatus(201); // Ou 200

        // Nettoyage
        Programmation::where([
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId
        ])->delete();
    }

    public function test_update_programmation()
    {
        $persId = Personnel::inRandomOrder()->first()->code_pers;
        $ecId = Ec::inRandomOrder()->first()->code_ec;
        $salleId = Salle::inRandomOrder()->first()->num_sale;

        // Créer donnée initiale
        Programmation::firstOrCreate(
            ['code_pers' => $persId, 'code_ec' => $ecId, 'num_salle' => $salleId],
            ['date' => '2099-11-10', 'heure_debut' => '10:00', 'heure_fin' => '12:00', 'nbre_heure' => 2, 'status' => 'init']
        );

        $updateData = [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId,
            'status' => 'terminé',
            'nbre_heure' => 4
        ];

        $response = $this->putJson("/api/programmations/update", $updateData);

        if ($response->status() !== 200) {
            dump($response->json());
        }
        $response->assertStatus(200);

        // Nettoyage
        Programmation::where(['code_pers' => $persId, 'code_ec' => $ecId, 'num_salle' => $salleId])->delete();
    }

    public function test_delete_programmation()
    {
        $persId = Personnel::inRandomOrder()->first()->code_pers;
        $ecId = Ec::inRandomOrder()->first()->code_ec;
        $salleId = Salle::inRandomOrder()->first()->num_sale;

        // Clean up any existing record with these composite keys
        \DB::table('programmation')->where([
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId
        ])->delete();

        // Force create via DB table to avoid Eloquent composite PK issues
        \DB::table('programmation')->insert([
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId,
            'date' => '2099-09-09',
            'heure_debut' => '14:00',
            'heure_fin' => '16:00',
            'nbre_heure' => 2,
            'status' => 'planifié',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify it exists 
        $this->assertDatabaseHas('programmation', [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId
        ]);

   
        $url = "/api/programmations/delete?code_pers=" . urlencode($persId) . "&code_ec=" . urlencode($ecId) . "&num_salle=" . urlencode($salleId);

        $response = $this->deleteJson($url);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('programmation', [
            'code_pers' => $persId,
            'code_ec' => $ecId,
            'num_salle' => $salleId
        ]);
    }
}
