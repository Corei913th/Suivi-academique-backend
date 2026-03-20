<?php

namespace Tests\Feature;

use Tests\TestCase;

class FiliereTest extends TestCase
{


    public function test_get_filiere_with_my_token()
    {
        $response = $this->getJson('/api/filieres');

        $response->assertStatus(200);
    }

    public function test_create_filiere_with_my_token()
    {
        $code = 'TEST-' . rand(1000, 9999);

        $data = [
            'code_filiere' => $code,
            'label_filiere' => 'Filière Test Automatique',
            'desc_filiere' => 'Créée par PHPUnit'
        ];

        
        $response = $this->postJson('/api/filieres', $data);

       
        $response->assertStatus(201);

        \App\Models\Filiere::destroy($code);
    }

    public function test_update_filiere_with_my_token()
    {
        $code = 'TEST-UPD-' . rand(1000, 9999);
        $filiere = \App\Models\Filiere::create([
            'code_filiere' => $code,
            'label_filiere' => 'Original Label',
            'desc_filiere' => 'Original Desc'
        ]);

        $updateData = [
            'label_filiere' => 'Label Modifié',
            'desc_filiere' => 'Desc Modifiée'
        ];

        $response = $this->putJson("/api/filieres/{$code}", $updateData);

        $response->assertStatus(200);

        $filiere->delete();
    }

    public function test_delete_filiere_with_my_token()
    {
        $code = 'TEST-DEL-' . rand(1000, 9999);
        \App\Models\Filiere::create([
            'code_filiere' => $code,
            'label_filiere' => 'To Delete',
            'desc_filiere' => 'To Delete'
        ]);


        $response = $this->deleteJson("/api/filieres/{$code}");


        $response->assertStatus(200);

        $this->assertDatabaseMissing('filiere', ['code_filiere' => $code]);
    }
}
