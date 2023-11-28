<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // php artisan test --filter UserTest::test_create_user
    private $response;
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"admin@lenna.ai",
            "password"=>"password",
            "name"=>"hai"
        ];
        $this->response = $this->post('/api/auth/login',$data);
    }
    public function test_login(): void
    {
        $response = $this->response;
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
                "login_at",
                "logout_at",
                "access_token",
                "token_type",
                "expires_in"
            ]
        ]);
    }

    // public function test_register(): void
    // {
    //     $data = [
    //         "email"=>"ssarifin@gmails.scoma",
    //         "password"=>"password",
    //         "name"=>"arifin",
    //         "roles"=>1
    //     ];

    //     $response = $this->post('/api/auth/register',$data);

    //     $user = User::where('name',$data['name']);
    //     $user_role = UserRole::where('user_id',$user->first()->id)->get();
    //     foreach ($user_role as $key => $value) {
    //         $value->delete();
    //     }
    //     $user->delete();
    //     $response->assertStatus(201);
    //     $response->assertJsonStructure([
    //         'data' => [
    //             'name',
    //             "email",
    //             "roles"=> [
    //                 '*'=> [
    //                     "name",
    //                     "permissions" => [
    //                         '*'=>["name"],
    //                     ]
    //                 ]
    //             ],
    //             "login_at",
    //             "logout_at",
    //         ]
    //     ]);
    //     ;
    // }

    public function test_logout(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
        ])->post('/api/auth/logout');

        $response->assertStatus(204);
    }
}
