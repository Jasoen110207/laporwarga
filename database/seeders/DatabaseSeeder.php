<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Semua akun demo memakai kata sandi: password
        User::create(['name' => 'Admin Kelurahan', 'email' => 'admin@laporwarga.test', 'password' => 'password', 'role' => 'admin']);
        $petugas = User::create(['name' => 'Petugas Lapangan', 'email' => 'petugas@laporwarga.test', 'password' => 'password', 'role' => 'petugas', 'phone' => '081200000001']);
        $warga = User::create(['name' => 'Siti Rahayu', 'email' => 'warga@laporwarga.test', 'password' => 'password', 'role' => 'warga', 'phone' => '081200000002', 'address' => 'RT 03 RW 05']);

        $categories = collect([
            ['Jalan & Infrastruktur', 'Jalan berlubang, lampu jalan mati, saluran tersumbat'],
            ['Kebersihan & Sampah', 'Tumpukan sampah, TPS penuh, got kotor'],
            ['Bantuan Sosial', 'Usulan penerima bantuan, data warga rentan'],
            ['Kesehatan', 'Posyandu, wabah penyakit, layanan puskesmas'],
            ['Keamanan Lingkungan', 'Gangguan keamanan, siskamling, penerangan rawan'],
            ['Air Bersih & Sanitasi', 'Air mati, pipa bocor, sanitasi bermasalah'],
        ])->mapWithKeys(fn ($c) => [$c[0] => Category::create(['name' => $c[0], 'slug' => Str::slug($c[0]), 'description' => $c[1]])]);

        $samples = [
            ['Jalan & Infrastruktur', 'Jalan berlubang dekat pasar', 'Lubang sedalam sekitar 20 cm di depan pasar, sudah dua motor terjatuh minggu ini.', 'Jl. Melati, depan Pasar Kliwon', 'selesai', 'Lubang sudah ditambal oleh tim PU. Terima kasih laporannya.'],
            ['Kebersihan & Sampah', 'Sampah menumpuk di TPS RW 05', 'Sampah tidak diangkut selama lima hari dan mulai berbau, banyak lalat.', 'TPS RW 05, Gang Kenanga', 'diproses', 'Sudah dijadwalkan pengangkutan oleh petugas kebersihan besok pagi.'],
            ['Bantuan Sosial', 'Usulan bantuan untuk lansia sebatang kara', 'Ibu Marni (72 tahun) tinggal sendiri dan belum menerima bantuan sosial apa pun.', 'RT 02 RW 03', 'baru', null],
            ['Air Bersih & Sanitasi', 'Air PDAM mati dua hari', 'Air tidak mengalir sejak Sabtu untuk seluruh RT 04.', 'RT 04 RW 01', 'baru', null],
            ['Keamanan Lingkungan', 'Lampu jalan gelap di gang rawan', 'Tiga lampu jalan mati sehingga gang sangat gelap saat malam.', 'Gang Anggrek', 'ditolak', 'Lokasi berada di wilayah kelurahan lain, silakan laporkan ke kelurahan setempat.'],
        ];

        foreach ($samples as [$cat, $title, $desc, $loc, $status, $reply]) {
            $report = Report::create([
                'user_id' => $warga->id,
                'category_id' => $categories[$cat]->id,
                'title' => $title,
                'description' => $desc,
                'location' => $loc,
                'status' => $status,
            ]);

            if ($reply) {
                $report->responses()->create(['user_id' => $petugas->id, 'message' => $reply, 'status_after' => $status]);
            }
        }
    }
}
