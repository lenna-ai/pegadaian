<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_index_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
        ])->get('/api/user');


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
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
                ]
            ]
        ]);
    }

    public function test_create_user(): void
    {
        $user = User::where('email','required@gmail.com')->get();
        if (count($user) > 0) {
            User::where('email','required@gmail.com')->delete();
        }
        $data = [
            'name'=>'string',
            'email'=>'required@gmail.com',
            'roles'=>2,
            'password'=>'password',
            'status'=>'offline'
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
        ])->post('/api/user',$data);
        $user = User::where('email','required@gmail.com')->get();

        $response->assertStatus(201);
        $this->assertEquals(count($user), 1);
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
            ]
        ]);
    }

    public function test_update_user(): void
    {
        $user = User::where('email','required@gmail.com')->get();
        if (count($user) < 1) {
            $data = [
                'name'=>'string',
                'email'=>'required@gmail.com',
                'roles'=>2,
                'password'=>'password',
                'status'=>'offline'
            ];

            $response = $this->withHeaders([
                'Authorization' => "Bearer {$this->response['data']['access_token']}",
            ])->post('/api/user',$data);
            $user = User::where('email','required@gmail.com')->get();
        }
        $data = [
            'name'=>'string',
            'email'=>'required@gmail.com',
            'roles'=>2,
            'password'=>'password',
            'status'=>'login'
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
        ])->put("/api/user/{$user[0]->id}",$data);
        $user = User::where('email','required@gmail.com')->get();

        $response->assertStatus(200);
        $this->assertEquals(count($user), 1);
        $this->assertEquals($response['data']['status'], 'login');
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
                "status",
                "login_at",
                "logout_at",
            ]
        ]);
    }

    public function test_delete_user(): void
    {
        $user = User::where('email','required@gmail.com')->get();
        if (count($user) < 1) {
            $data = [
                'name'=>'string',
                'email'=>'required@gmail.com',
                'roles'=>2,
                'password'=>'password',
                'status'=>'offline'
            ];

            $response = $this->withHeaders([
                'Authorization' => "Bearer {$this->response['data']['access_token']}",
            ])->post('/api/user',$data);
            $user = User::where('email','required@gmail.com')->get();
        }
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
        ])->delete("/api/user/{$user[0]->id}");
        $user = User::where('email','required@gmail.com')->get();

        $response->assertStatus(200);
        $this->assertEquals(count($user), 0);
        $response->assertJsonStructure([
            'data' => [
                'name',
                "email",
                "roles",
                "login_at",
                "logout_at",
            ]
        ]);
    }

    public function tearDown(): void
    {
        $user = User::where('email','required@gmail.com')->get();
        if (count($user) > 0) {
            User::where('email','required@gmail.com')->delete();
        }
    }
}
