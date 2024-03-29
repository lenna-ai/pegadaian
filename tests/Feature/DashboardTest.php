<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    // php artisan test --filter DashboardTest::test_count_tag_operator
    // php artisan test --filter DashboardTest::test_count_category_operator
    private $response;
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"admin@lenna.ai",
            "password"=>"secret",
            "name"=>"hai"
        ];
        $this->response = $this->post('/api/auth/login',$data);
    }

    public function test_count_category_operator(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/dashboard/operator/count_category/2023-01-01/2024-01-10');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'category',
                    'count_category',
                    'percentage',
                ]
            ]
        ]);
    }

    public function test_count_tag_operator(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/dashboard/operator/count_tag/2023-01-01/2024-01-10');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'tag',
                    'count_tag',
                    'percentage',
                ]
            ]
        ]);
    }
}
