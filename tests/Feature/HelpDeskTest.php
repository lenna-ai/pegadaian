<?php

namespace Tests\Feature;

use App\Models\HelpDesk;
use App\Models\Operator;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HelpDeskTest extends TestCase
{
    // php artisan test --filter HelpDeskTest::test_index_helpdesk
    // php artisan test --filter HelpDeskTest::test_create_helpdesk_outlet_branch_code
    // php artisan test --filter HelpDeskTest::test_count_tag
    private $response;
    private $responseAdmin;
    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"helpdesk@lenna.ai",
            "password"=>"secret"
        ];
        $this->response = $this->post('/api/auth/login',$data);
        $this->responseAdmin = User::where([
            ["email",'=',"admin@lenna.ai"],
            ["password",'=','$2y$12$VhY9UXOqpxMg2sGYPv/fiOEmM58uXp6TodbfocTheR0eKLYcasVA6']
        ])->first();
    }

    public function test_index_helpdesk(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/helpdesk/desc/2024-01-01/2024-01-15?page=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'id',
                    'ticket_number',
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
            'ticket_number'=>'ARIA-123232',
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
                'id',
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

    public function test_detail_helpdesk(): void
    {
        // create helpdesk nya buat sementara
        $this->test_create_helpdesk();
        $helpdesk = HelpDesk::where('ticket_number', 'ARIA-123232')->where('branch_code', 202)->first();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/helpdesk/detail/' . $helpdesk->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'ticket_number',
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
        ]);

        $helpdesk->delete();
    }

    public function test_update_helpdesk(): void
    {
        // create helpdesk nya buat sementara
        $this->test_create_helpdesk();
        $helpdesk = HelpDesk::where('ticket_number', 'ARIA-123232')->where('branch_code', 202)->first();

        $data = [
            'ticket_number'=>'ARIA-123232',
            'branch_code'=>202,
            'branch_name'=>'prod',
            'branch_name_staff'=>'production',
            'branch_phone_number'=>'0886622891027',
            'date_to_call'=>'2023-12-12 12:00',
            'call_duration'=>16,
            'result_call'=>'anything test',
            'name_agent' => 'helpdesk',
            'status'=>'⁠Analisis',
            'parent_branch' => 'CP MEDAN UTAMA',
            'category'=>'Konfirmasi Surat',
            'tag' => 'Internal',
            'input_voice_call'=>null
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/helpdesk/update/' . $helpdesk->id,$data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
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

        $helpdesk->delete();
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

    public function test_count_category(): void
    {
        $response = $this->actingAs(User::find($this->responseAdmin->id))->get('/api/dashboard/helpdesk/count_category/2023-01-01/2024-01-10');

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
        $response = $this->actingAs(User::find($this->responseAdmin->id))->get('/api/dashboard/helpdesk/count_tag/2023-01-01/2024-01-10');

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
