<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\StatusTrack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // [
            //     'name' => '⁠Analisis',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Sedang di tangani',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Menunggu pelanggan',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Konfirmasi penyelesaian',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Menunggu support',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Eskalasi',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Pelapor tidak response',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Selesai',
            //     'owned' => 'helpdesk',
            // ],
            // [
            //     'name' => '⁠Belum ada tiket',
            //     'owned' => 'helpdesk',
            // ],
            [
                'name' => 'DITOLAK',
                'owned' => 'outbound_ask_more',
            ],
            [
                'name' => 'SALAH SAMBUNG',
                'owned' => 'outbound_ask_more',
            ],
            [
                'name' => 'TERHUBUNG',
                'owned' => 'outbound_ask_more',
            ],
            [
                'name' => 'TIDAK AKTIF',
                'owned' => 'outbound_ask_more',
            ],
            [
                'name' => 'TIDAK MENJAWAB',
                'owned' => 'outbound_ask_more',
            ],
            [
                'name' => 'Cancel/Batal',
                'owned' => 'outbound_leads',
            ],
            [
                'name' => 'Lanjut',
                'owned' => 'outbound_leads',
            ],
            [
                'name' => 'No Tidak Aktif / Salah',
                'owned' => 'outbound_leads',
            ],
            [
                'name' => 'No tidak Respon',
                'owned' => 'outbound_leads',
            ],
            [
                'name' => 'Sudah Dihubungi team Mikro',
                'owned' => 'outbound_leads',
            ],
            [
                'name' => 'DIANGKAT',
                'owned' => 'outbound_agency',
            ],
            [
                'name' => 'PANGGILAN DITOLAK',
                'owned' => 'outbound_agency',
            ],
            [
                'name' => 'SALAH SAMBUNG',
                'owned' => 'outbound_agency',
            ],
            [
                'name' => 'TIDAK AKTIF',
                'owned' => 'outbound_agency',
            ],
            [
                'name' => 'TIDAK DIANGKAT',
                'owned' => 'outbound_agency',
            ],
            [
                'name' => 'Pelapor Belum Bisa Cek / Konfirmasi Penyelesaian',
                'owned' => 'outbound_confirmation_ticket',
            ],
            [
                'name' => 'Pelapor Belum Menjawab Outbound Call',
                'owned' => 'outbound_confirmation_ticket',
            ],
            [
                'name' => 'penyelesaian belum sesuai',
                'owned' => 'outbound_confirmation_ticket',
            ],
            [
                'name' => 'Tidak Perlu Outbound',
                'owned' => 'outbound_confirmation_ticket',
            ],
        ];
        foreach ($data as $key => $value) {
            StatusTrack::create($value);
        }
    }
}
