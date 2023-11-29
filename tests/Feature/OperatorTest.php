<?php

namespace Tests\Feature;

use App\Models\Operator;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OperatorTest extends TestCase
{
    // php artisan test --filter OperatorTest::test_create_operator
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

    public function test_create_operator(): void
    {

        $data = [
            'name_agent' => 'admin',
            'name_customer'=>'required',
            'date_to_call'=> "22/10/2023",
            'call_duration'=>20,
            'result_call'=>'required',
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/operator',$data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'name_agent',
                "name_customer",
                "date_to_call",
                "call_duration",
                "result_call",
            ]
        ]);
    }

    public function tearDown(): void
    {
        $user = Operator::where([
            'name_agent' => 'admin',
            'name_customer'=>'required'
        ])->get();
        if (count($user) > 0) {
            Operator::where([
                'name_agent' => 'admin',
                'name_customer'=>'required'
            ])->delete();
        }
    }
}
