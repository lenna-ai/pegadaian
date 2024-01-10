<?php

namespace Tests\Feature;

use App\Models\OutBound;
use App\Models\OutBoundConfirmationTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutBoundTest extends TestCase
{
    // php artisan test --filter OutBoundTest::test_index_outbound_by_page
    // php artisan test --filter OutBoundTest::test_detail_outbound_by_page
    // php artisan test --filter OutBoundTest::test_index_outbound_confirmation_ticket
    // php artisan test --filter OutBoundTest::test_detail_outbound_confirmation_ticket
    // php artisan test --filter OutBoundTest::test_category_outbound
    // php artisan test --filter OutBoundTest::test_statusTrack_outbound_by_page
    // php artisan test --filter OutBoundTest::test_create_outbound_by_page
    // php artisan test --filter OutBoundTest::test_update_outbound_by_page
    // php artisan test --filter OutBoundTest::test_create_outbound_confirmation_ticket
    // php artisan test --filter OutBoundTest::test_update_outbound_confirmation_ticket
    // php artisan test --filter OutBoundTest::test_count_tag

    private $response;
    private $responseAdmin;

    public function setUp(): void
    {
        parent::setUp();
        $data = [
            "email"=>"outbound@lenna.ai",
            "password"=>"secret"
        ];
        $this->response = $this->post('/api/auth/login',$data);
        $dataAdmin = [
            "email"=>"admin@lenna.ai",
            "password"=>"secret"
        ];
        $this->responseAdmin = $this->post('/api/auth/login',$dataAdmin);
    }

    public function test_index_outbound_by_page(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outbound/agency'); // possible page: agency, ask_more, leads

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'id',
                    'name',
                    "call_time",
                    "call_duration",
                    "status",
                ]
            ]
        ]);
    }

    public function test_detail_outbound_by_page(): void
    {
        // create outbound nya buat sementara
        $outbound = $this->test_create_outbound_by_page();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outbound/agency/detail/' . $outbound['id']); // possible page: agency, ask_more, leads

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                "call_time",
                "call_duration",
                "status",
            ]
        ]);

        OutBound::find($outbound['id'])->delete();
    }

    public function test_index_outbound_confirmation_ticket(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outbound/confirmation-ticket');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'id',
                    'name_agent',
                    'ticket_number',
                    'category',
                    "status",
                    "call_time",
                    "call_duration",
                    "result_call",
                ]
            ]
        ]);
    }

    public function test_detail_outbound_confirmation_ticket(): void
    {
        // create outbound nya buat sementara
        $outbound = $this->test_create_outbound_confirmation_ticket();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outbound/confirmation-ticket/detail/' . $outbound['id']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'ticket_number',
                'category',
                "status",
                "call_time",
                "call_duration",
                "result_call",
            ]
        ]);

        OutBoundConfirmationTicket::find($outbound['id'])->delete();
    }

    public function test_category_outbound(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outlet/category/outbound');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'name',
                ]
            ]
        ]);
    }

    public function test_statusTrack_outbound_by_page(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->get('/api/outbound/agency/outlet/statusTrack'); // possible page: agency, ask_more, leads, confirmation-ticket

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*'=>[
                    'name',
                ]
            ]
        ]);
    }

    public function test_create_outbound_by_page()
    {

        $data = [
            'name'=>'Randika Test',
            'call_time'=>'2023-10-22 10:11',
            'call_duration'=>'45',
            'status'=>'DIANGKAT',
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/outbound/agency',$data); // possible page: agency, ask_more, leads

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'name',
                "call_time",
                "call_duration",
                "status",
            ]
        ]);

        return $response->json('data');
    }

    public function test_update_outbound_by_page(): void
    {
        // create outbound nya buat sementara
        $outbound = $this->test_create_outbound_by_page();

        $data = [
            'name'=>'Randika Test Updated',
            'call_time'=>'2023-10-22 10:11',
            'call_duration'=>'45',
            'status'=>'DIANGKAT',
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/outbound/agency/update/' . $outbound['id'],$data); // possible page: agency, ask_more, leads

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                "call_time",
                "call_duration",
                "status",
            ]
        ]);

        OutBound::find($outbound['id'])->delete();
    }

    public function test_create_outbound_confirmation_ticket()
    {

        $data = [
            'name_agent'=>'Randika Agent',
            'ticket_number'=>'TICKET-1234',
            'category'=>'Wallet',
            'status'=>'Pelapor Belum Bisa Cek / Konfirmasi Penyelesaian',
            'call_time'=>'2023-10-22 10:11',
            'call_duration'=>'45',
            'result_call'=>'OKE',
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/outbound/confirmation-ticket',$data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'name_agent',
                'ticket_number',
                'category',
                'status',
                "call_time",
                "call_duration",
                "result_call",
            ]
        ]);

        return $response->json('data');
    }

    public function test_update_outbound_confirmation_ticket(): void
    {
        // create outbound nya buat sementara
        $outbound = $this->test_create_outbound_confirmation_ticket();

        $data = [
            'name_agent'=>'Randika Agent Updated',
            'ticket_number'=>'TICKET-1234',
            'category'=>'Wallet',
            'status'=>'Pelapor Belum Bisa Cek / Konfirmasi Penyelesaian',
            'call_time'=>'2023-10-22 10:11',
            'call_duration'=>'45',
            'result_call'=>'OKE',
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->response['data']['access_token']}",
            'Accept'=>'application/json'
        ])->post('/api/outbound/confirmation-ticket/update/' . $outbound['id'],$data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name_agent',
                'ticket_number',
                'category',
                'status',
                "call_time",
                "call_duration",
                "result_call",
            ]
        ]);

        OutBoundConfirmationTicket::find($outbound['id'])->delete();
    }

    public function test_count_category(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->responseAdmin['data']['access_token']}",
        ])->get('/api/dashboard/outbound/count_category/2023-01-01/2024-01-10');

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

    public function tearDown(): void
    {
        $outboundByPage = OutBound::where(['name' => 'Randika Test'])->first();
        if ($outboundByPage) {
            $outboundByPage->delete();
        }

        $outboundConfirmationTicket = OutBoundConfirmationTicket::where([
            'name_agent' => 'Randika Agent',
            'ticket_number' => 'TICKET-1234',
        ])->first();
        if ($outboundConfirmationTicket) {
            $outboundConfirmationTicket->delete();
        }
    }
}
