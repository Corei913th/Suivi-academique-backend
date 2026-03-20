<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = new User;
        $user->id = 1;
        $user->code_pers = 'PERS-001';
        $user->name = 'Test User';
        $user->email = 'test@example.com';

        Sanctum::actingAs($user, ['*']);
    }
}
