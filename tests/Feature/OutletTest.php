<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutletTest extends TestCase
{
    // php artisan test --filter OutletTest
    // php artisan test --filter OutletTest::test_create_category
    private $helpdesk;
    private $operator;
    public function setUp(): void
    {
        parent::setUp();
        $dataHelpDesk = [
            "email"=>"helpdesk@lenna.ai",
            "password"=>"secret"
        ];
        $this->helpdesk = $this->post('/api/auth/login',$dataHelpDesk);

        $dataOperator = [
            "email"=>"operator@lenna.ai",
            "password"=>"secret"
        ];
        $this->operator = $this->post('/api/auth/login',$dataOperator);
    }

    public function test_category_helpdesk(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->helpdesk['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outlet/category/helpdesk');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'name',
                    "owned",
                ]
            ]
        ]);
    }

    public function test_category_operator(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->operator['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outlet/category/operator');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'name',
                    "owned",
                ]
            ]
        ]);
    }

    public function test_create_category(): void
    {
        $data = [
            'name'=>'tuyuls',
            'owned'=>'operator'
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->operator['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/outlet/category',$data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'name',
                "owned",
            ]
        ]);

    }

    public function tearDown(): void
    {
        Category::where([
            ['name','=','tuyuls'],
            ['owned','=','operator']
        ])->delete();
    }
}
