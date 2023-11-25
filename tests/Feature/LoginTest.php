<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login(): void
    {
        $data = [
            "email"=>"ssarifin@gmails.scoma",
            "password"=>"password",
            "name"=>"arifin"
        ];
        $response = $this->post('/api/auth/login',$data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'name',
                "email",
                "roles"=> [
                    '*'=> [
                        "name",
                        "permissions" => [
                            '*'=>["name"],
                        ]
                    ]
                ],
                "access_token",
                "token_type",
                "expires_in"
            ]
        ]);
    }
}
