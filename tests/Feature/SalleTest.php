<?php

namespace Tests\Feature;

use App\Models\Salle;
use Tests\TestCase;

class SalleTest extends TestCase
{
    public function test_get_salles()
    {
        $response = $this->getJson('/api/salles');
        $response->assertStatus(200);
    }

    public function test_create_salle()
    {
        $code = 'SAL-TEST-'.rand(100, 999);

        $data = [
            'num_sale' => $code,
            'contenance' => 50,
            'statut' => 'disponible', // Enum
        ];

        $response = $this->postJson('/api/salles', $data);

        $response->assertStatus(201);

        Salle::destroy($code);
    }

    public function test_update_salle()
    {
        $code = 'SAL-UPD-'.rand(100, 999);
        $salle = Salle::create([
            'num_sale' => $code,
            'contenance' => 30,
            'statut' => 'maintenance',
        ]);

        $updateData = ['contenance' => 100];

        $response = $this->putJson("/api/salles/{$code}", $updateData);

        $response->assertStatus(200);

        $salle->delete();
    }

    public function test_delete_salle()
    {
        $code = 'SAL-DEL-'.rand(100, 999);
        Salle::create([
            'num_sale' => $code,
            'contenance' => 10,
            'statut' => 'occupée',
        ]);

        $response = $this->deleteJson("/api/salles/{$code}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('salle', ['num_sale' => $code]);
    }
}
