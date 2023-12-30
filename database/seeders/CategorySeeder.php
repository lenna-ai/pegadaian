<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::factory(3)->create();

        // Category for Outbound
        $data = [
            [
                'name' => 'Gadai KCA',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Pembukaan Rekening',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash - Top Up',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'MPO (Pasca & Prabayar) - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Sharing Fee Agen - Fee Tidak Masuk',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Wallet',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Wallet - Top Up Wallet Gagal',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Akuntansi & Tresuri',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Akuntansi & Tresuri - Kesalahan Pembukuan',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Amanah',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Amanah - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Amanah - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Aplikasi Agen Pegadaian',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Ekspress Loan / KUR',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Ekspress Loan / KUR - Pencairan Non Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Ekspress Loan / KUR - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Ekspress Loan / KUR - Workflow Berhenti',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Haji - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Haji - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Collection Channeling',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Collection Channeling - Pensuksesan transaksi gagal',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Digital Lending - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Digital Lending - Pengajuan',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Disburse',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Disburse - Bank Himbara dan BCA (Direct) Status SP',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Disburse - Bank Non Himbara (Agregrator OY!) Abnormal Status YP',
                'owned' => 'outbound',
            ],
            [
                'name' => 'EDC',
                'owned' => 'outbound',
            ],
            [
                'name' => 'EDC - Pembukaan MA Bank Pusat dan BDP Bank Pusat',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Emasku Ultimate',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Emasku Ultimate - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Penyelesaian BJ Masalah',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Perubahan data barang jaminan',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Perubahan data kredit (CIF, Nama, No Rek Pendamping)',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA - SBG/SBR QR Code',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA UMI - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai KCA UMI - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Release saldo blokir',
                'owned' => 'outbound',
            ],
            [
                'name' => 'ID Promosi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kios Mikro - Lainnya',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida - Perubahan data kredit (CIF, Nama, No Rek Pendamping)',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Multiguna',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Multiguna - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Reguler',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Reguler - Koreksi pengajuan Klaim',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Reguler - Perubahan data kredit (CIF, Nama, No Rek Pendamping)',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi UMI - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi UMI - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi UMI - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kresna - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'KYC & Akun Premium',
                'owned' => 'outbound',
            ],
            [
                'name' => 'KYC & Akun Premium - Aktivasi Akun Premium',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Lainnya - Lainnya',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Los Prime - KUR',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Karyawan - SSO Passion',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Karyawan - Unit Kerja',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Perubahan Nomor HP',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Registrasi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Reset PIN Fitur Finansial',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Unlink CIF',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Upgrade Akun / KYC',
                'owned' => 'outbound',
            ],
            [
                'name' => 'MPO (Pasca & Prabayar)',
                'owned' => 'outbound',
            ],
            [
                'name' => 'MPO (Pasca & Prabayar) - Pembatalan Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'MPO (Pasca & Prabayar) - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'MPO (Pasca & Prabayar) - Top Up e-wallet Pending',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Open Mulia',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Pembayaran Uang Muka',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Tidak bisa terima order Mulia di Passion',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Network / Jaringan',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Network / Jaringan - NMS - Gangguan / Offline',
                'owned' => 'outbound',
            ],
            [
                'name' => 'OTP - OTP Salah',
                'owned' => 'outbound',
            ],
            [
                'name' => 'OTP - OTP Tidak Masuk',
                'owned' => 'outbound',
            ],
            [
                'name' => 'PACS',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Permohonan Pembukaan MA - Pembukaan MA',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn - Promosi / Pemasaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tabungan Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Remittance - Refund Transaksi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Remittance Delima Cash In/Cash Out - Perubahan No HP Penerima Delima',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Remittance WU - Refund Full Amount H+1',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Remittance WU - Refund Principal',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Buyback Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Buyback Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Saldo Blokir',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Top Up',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Transaksi via Outlet - Refund Transaksi Outlet',
                'owned' => 'outbound',
            ],
            [
                'name' => 'amanah',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Amanah - Promosi / Pemasaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum BPKB - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Emas - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Haji',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tabungan Emas - Pencairan Non-Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tabungan Emas - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tasjily Tanah',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tasjily Tanah - Pencairan Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Amanah - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'ARIANA',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum BPKB',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Emas - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Arrum Haji - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash - Pemindahbukuan Rekening G-Cash BRI',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash - Pencairan G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash - Top Up G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'G-Cash - Transfer',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Efek',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Efek - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Gadai Tabungan Emas - Pengajuan Gadai Tabungan Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Galeri 24',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kode Promo / Voucher',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Krasida - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi Ultramikro',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Kreasi UMI',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Aktivasi Finansial',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Blokir User',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Buka Blokir User',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Ganti Email',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Kendala / Gagal Login',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Link CIF',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Manajemen Akun Nasabah / CIF - Verifikasi Email',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Emasku - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Pembayaran Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Mulia Ultimate - Unduh Surat Perjanjian',
                'owned' => 'outbound',
            ],
            [
                'name' => 'OTP',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Pemasaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn - Pembayaran Melalui Virtual Account',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Rahn Tabungan Emas - Pembayaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Safety Deposit Box (SDB)',
                'owned' => 'outbound',
            ],
            [
                'name' => 'SMS Blast',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Buyback Non Tunai',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Cetak Emas',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Promosi / Pemasaran',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Top Up Melalui G-Cash',
                'owned' => 'outbound',
            ],
            [
                'name' => 'Tabungan Emas - Top Up Melalui Virtual Account',
                'owned' => 'outbound',
            ],
        ];

        foreach ($data as $key => $value) {
            Category::create($value);
        }
    }
}
