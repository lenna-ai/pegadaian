<?php

namespace Tests\Feature;

use App\Models\Operator;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Support\Str;

class OperatorTest extends TestCase
{
    // php artisan test --filter OperatorTest::test_index_operator
    private $response;
    private $responseAdmin;
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"operator@lenna.ai",
            "password"=>"secret"
        ];
        $this->response = $this->post('/api/auth/login',$data);

        $this->responseAdmin = User::where([
            ["email",'=',"admin@lenna.ai"],
            ["password",'=','$2y$12$VhY9UXOqpxMg2sGYPv/fiOEmM58uXp6TodbfocTheR0eKLYcasVA6']
        ])->first();
    }

    public function test_index_operator(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}"
        ])->get('/api/operator');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'id',
                    'name_agent',
                    "name_customer",
                    "date_to_call",
                    "call_duration",
                    "result_call",
                ]
            ]
        ]);
    }

    public function test_detail_operator(): void
    {
        // create operator nya buat sementara
        $operator = $this->test_create_operator();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/operator/detail/' . $operator['id']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name_agent',
                "name_customer",
                "date_to_call",
                "call_duration",
                "result_call",
            ]
        ]);

        Operator::where('id', $operator['id'])->delete();
    }

    public function test_create_operator(): array
    {

        $data = [
            'name_agent' => 'admin',
            'name_customer'=>'required-' . Str::random(2),
            'date_to_call'=> "2023-10-22 13:30",
            'call_duration'=>20,
            'result_call'=>'required',
            'category'=>'Konfirmasi Surat',
            'tag' => 'Internal',
            'input_voice_call'=>UploadedFile::fake()->create('filename.mp3'),
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/operator',$data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name_agent',
                "name_customer",
                "date_to_call",
                "call_duration",
                "result_call",
                "category",
                "tag",
                "input_voice_call",
            ]
        ]);

        return $response->json('data');
    }

    public function test_update_operator(): void
    {
        // create operator nya buat sementara
        $operator = $this->test_create_operator();

        $data = [
            'name_agent' => 'admin',
            'name_customer'=>'required',
            'date_to_call'=> "2023-10-22 13:30",
            'call_duration'=>20,
            'result_call'=>'required',
            'category'=>'Konfirmasi Surat',
            'tag' => 'Internal',
            'input_voice_call'=>UploadedFile::fake()->create('filename.mp3'),
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/operator/update/' . $operator['id'],$data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name_agent',
                "name_customer",
                "date_to_call",
                "call_duration",
                "result_call",
                "category",
                "tag",
                "input_voice_call",
            ]
        ]);

        Operator::where('id', $operator['id'])->delete();
    }

    public function test_count_category(): void
    {
        $response = $this->actingAs(User::find($this->responseAdmin->id))->get('/api/dashboard/operator/count_category/2023-01-01/2024-01-10');

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

    public function test_count_tag(): void
    {
        $response = $this->actingAs(User::find($this->responseAdmin->id))->get('/api/dashboard/operator/count_tag/2023-01-01/2024-01-10');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'tag',
                    'percentage',
                ]
            ]
        ]);
    }

    public function tearDown(): void
    {
        $user = Operator::where([
            'name_agent' => 'operator',
            'name_customer'=>'required'
        ])->get();
        if (count($user) > 0) {
            Operator::where([
                'name_agent' => 'operator',
                'name_customer'=>'required'
            ])->delete();
        }
    }
}
