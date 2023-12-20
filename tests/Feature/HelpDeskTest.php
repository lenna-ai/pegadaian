<?php

namespace Tests\Feature;

use App\Models\HelpDesk;
use App\Models\Operator;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HelpDeskTest extends TestCase
{
    // php artisan test --filter HelpDeskTest::test_create_helpdesk
    // php artisan test --filter HelpDeskTest::test_create_helpdesk_outlet_branch_code
    private $response;
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"helpdesk@lenna.ai",
            "password"=>"secret"
        ];
        $this->response = $this->post('/api/auth/login',$data);
    }

    public function test_index_helpdesk(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/helpdesk');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'branch_code',
                    "branch_name",
                    "branch_name_staff",
                    "branch_phone_number",
                    "date_to_call",
                    "call_duration",
                    "result_call",
                    "name_agent",
                    "input_voice_call",
                ]
            ]
        ]);
    }

    public function test_create_helpdesk(): void
    {

        $data = [
            'branch_code'=>202,
            'branch_name'=>'prod',
            'branch_name_staff'=>'production',
            'branch_phone_number'=>'0886622891027',
            'date_to_call'=>'2023-12-12 12:00',
            'call_duration'=>16,
            'result_call'=>'anything',
            'name_agent' => 'helpdesk',
            'status'=>'⁠Analisis',
            'parent_branch' => 'CP MEDAN UTAMA',
            'category'=>'Konfirmasi Surat',
            'tag' => 'Internal',
            'input_voice_call'=>UploadedFile::fake()->create('filename.mp3')
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/helpdesk',$data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'branch_code',
                "branch_name",
                "branch_name_staff",
                "branch_phone_number",
                "date_to_call",
                "call_duration",
                "result_call",
                "name_agent",
                'status',
                'parent_branch',
                'category',
                'tag',
                "input_voice_call",
            ]
        ]);
    }

    public function test_create_helpdesk_outlet_statustrack(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/helpdesk/outlet/statusTrack');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'name'
                ],
            ]
        ]);
    }

    public function test_create_helpdesk_outlet_parentBranch(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/helpdesk/outlet/parent_branch');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'parent_branch'
                ],
            ]
        ]);
    }

    public function test_helpdesk_outlet_outlet_name(): void
    {
        $parent_branch = "CP MEDAN UTAMA";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get("/api/helpdesk/outlet/outlet_name?parent_branch=$parent_branch");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'outlet_name'
                ],
            ]
        ]);
    }

    public function test_helpdesk_outlet_branch_code(): void
    {
        $parent_branch = "CP MEDAN UTAMA";
        $outlet_name = "UPC MEDAN PLASA";
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get("/api/helpdesk/outlet/branch_code?parent_branch=$parent_branch&outlet_name=$outlet_name");

        $response->assertStatus(200);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'branch_code'
                ],
            ]
        ]);
    }

    public function tearDown(): void
    {
        $user = HelpDesk::where([
            'name_agent' => 'helpdesk',
            'branch_code'=>202
        ])->get();
        if (count($user) > 0) {
            HelpDesk::where([
                'name_agent' => 'helpdesk',
                'branch_code'=>202
            ])->delete();
        }
    }
}
