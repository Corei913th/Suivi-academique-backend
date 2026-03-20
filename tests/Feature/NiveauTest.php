<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Niveau;
use App\Models\Filiere;

class NiveauTest extends TestCase
{
    // Token d'accès

    public function test_get_niveaux()
    {
        $response = $this->getJson('/api/niveaux');
        $response->assertStatus(200);
    }

    public function test_create_niveau()
    {
        // Création d'un test ID unique
        $filiereCode = Filiere::inRandomOrder()->first()->code_filiere;

        $code = 'TEST-' . rand(1000, 9999);
        $data = [
            'code_niveau' => $code,
            'label_niveau' => 'Niveau Test ' . rand(100, 999),
            'desc_niveau' => 'Description test',
            'code_filiere' => $filiereCode
        ];

        $response = $this->postJson('/api/niveaux', $data);

        $response->assertStatus(201);

       
        $id = $response->json('code_niveau'); 
        if ($id) {
            Niveau::destroy($id);
        }
    }

    // Pour l'update et delete, on doit créer une ressource temporaire d'abord pour ne pas casser les vraies données
    public function test_update_niveau()
    {
        $filiereCode = Filiere::inRandomOrder()->first()->code_filiere;
        $niveau = Niveau::create([
            'code_niveau' => 'UPD-' . rand(1000, 9999),
            'label_niveau' => 'Old Label',
            'desc_niveau' => 'Old Desc',
            'code_filiere' => $filiereCode
        ]);

        $updateData = ['label_niveau' => 'New Label'];

        $response = $this->putJson("/api/niveaux/{$niveau->code_niveau}", $updateData);

        $response->assertStatus(200);

        $niveau->delete();
    }

    public function test_delete_niveau()
    {
        $filiereCode = Filiere::inRandomOrder()->first()->code_filiere;
        $niveau = Niveau::create([
            'code_niveau' => 'DEL-' . rand(1000, 9999),
            'label_niveau' => 'To Delete',
            'desc_niveau' => 'To Delete',
            'code_filiere' => $filiereCode
        ]);

        $response = $this->deleteJson("/api/niveaux/{$niveau->code_niveau}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('niveau', ['code_niveau' => $niveau->code_niveau]);
    }
}
