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
            '⁠Analisis',
            '⁠Sedang di tangani',
            '⁠Menunggu pelanggan',
            '⁠Konfirmasi penyelesaian',
            '⁠Menunggu support',
            '⁠Eskalasi',
            '⁠Pelapor tidak response',
            '⁠Selesai',
            '⁠Belum ada tiket',
        ];
        foreach ($data as $key => $value) {
            StatusTrack::create([
                'name'=>$value
            ]);
        }
    }
}
