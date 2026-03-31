<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Kader;
use App\Models\Keluarga;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // 1. AKUN ADMIN
        // ========================
        $adminUser = User::create([
            'name'              => 'Administrator',
            'username'          => 'admin',
            'email'             => 'admin@sidadompas.id',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'status'            => true,
            'email_verified_at' => now(),
        ]);

        Admin::create([
            'user_id'    => $adminUser->id,
            'nama_admin' => 'Administrator SIDA',
        ]);

        // ========================
        // 2. AKUN KEPALA DESA
        // ========================
        User::create([
            'name'              => 'Bpk. Suryadi',
            'username'          => 'kades1',
            'email'             => 'kades@sidadompas.id',
            'password'          => Hash::make('password'),
            'role'              => 'kades',
            'status'            => true,
            'email_verified_at' => now(),
        ]);

        // ========================
        // 3. AKUN KADER 1 + DATA
        // ========================
        $kaderUser1 = User::create([
            'name'              => 'Ibu Siti Aminah',
            'username'          => 'kader1',
            'email'             => 'kader1@sidadompas.id',
            'password'          => Hash::make('password'),
            'role'              => 'kader',
            'status'            => true,
            'email_verified_at' => now(),
        ]);

        $kader1 = Kader::create([
            'user_id'    => $kaderUser1->id,
            'nama_kader' => 'Siti Aminah',
            'wilayah'    => 'RT 01 / RW 01',
            'no_hp'      => '081234567890',
        ]);

        // Dasawisma Kader 1
        $dasawisma1 = Dasawisma::create([
            'kader_id'       => $kader1->id,
            'nama_dasawisma' => 'Dasawisma Melati',
            'rt'             => '01',
            'rw'             => '01',
            'desa'           => 'Desa Dompas',
        ]);

        // KK 1 - Disetujui
        $kk1 = Keluarga::create([
            'dasawisma_id'       => $dasawisma1->id,
            'no_kk'              => '6208010101000001',
            'nama_kepala_keluarga' => 'Budi Santoso',
            'jumlah_anggota'     => 4,
        ]);
        $anggotaKK1 = [
            ['nik' => '6208010101010001', 'nama_anggota' => 'Budi Santoso', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1980-05-10', 'agama' => 'Islam', 'pendidikan' => 'SMA/SMK', 'pekerjaan' => 'Petani', 'status_dalam_keluarga' => 'Kepala Keluarga', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101010002', 'nama_anggota' => 'Sari Dewi', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1983-08-22', 'agama' => 'Islam', 'pendidikan' => 'SMP', 'pekerjaan' => 'Ibu Rumah Tangga', 'status_dalam_keluarga' => 'Istri', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101010003', 'nama_anggota' => 'Andi Santoso', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2005-03-15', 'agama' => 'Islam', 'pendidikan' => 'SMA/SMK', 'pekerjaan' => 'Pelajar/Mahasiswa', 'status_dalam_keluarga' => 'Anak', 'status_perkawinan' => 'Belum Kawin'],
            ['nik' => '6208010101010004', 'nama_anggota' => 'Putri Santoso', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2020-11-08', 'agama' => 'Islam', 'pendidikan' => 'Tidak Sekolah', 'pekerjaan' => 'Tidak Bekerja', 'status_dalam_keluarga' => 'Anak', 'status_perkawinan' => 'Belum Kawin'],
        ];
        foreach ($anggotaKK1 as $a) $kk1->anggotaKeluargas()->create($a);
        Verifikasi::create(['keluarga_id' => $kk1->id, 'admin_id' => 1, 'tanggal_verifikasi' => '2026-03-20', 'status_verifikasi' => 'disetujui', 'catatan' => null]);

        // KK 2 - Menunggu
        $kk2 = Keluarga::create([
            'dasawisma_id'         => $dasawisma1->id,
            'no_kk'                => '6208010101000002',
            'nama_kepala_keluarga' => 'Hendra Kurniawan',
            'jumlah_anggota'       => 3,
        ]);
        $anggotaKK2 = [
            ['nik' => '6208010101020001', 'nama_anggota' => 'Hendra Kurniawan', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1975-12-01', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'PNS', 'status_dalam_keluarga' => 'Kepala Keluarga', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101020002', 'nama_anggota' => 'Ratna Sari', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1978-06-17', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Swasta', 'status_dalam_keluarga' => 'Istri', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101020003', 'nama_anggota' => 'Fajar Kurniawan', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2002-09-24', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Pelajar/Mahasiswa', 'status_dalam_keluarga' => 'Anak', 'status_perkawinan' => 'Belum Kawin'],
        ];
        foreach ($anggotaKK2 as $a) $kk2->anggotaKeluargas()->create($a);
        Verifikasi::create(['keluarga_id' => $kk2->id, 'status_verifikasi' => 'menunggu']);

        // ========================
        // 4. AKUN KADER 2 + DATA
        // ========================
        $kaderUser2 = User::create([
            'name'              => 'Bpk. Ahmad Fauzi',
            'username'          => 'kader2',
            'email'             => 'kader2@sidadompas.id',
            'password'          => Hash::make('password'),
            'role'              => 'kader',
            'status'            => true,
            'email_verified_at' => now(),
        ]);

        $kader2 = Kader::create([
            'user_id'    => $kaderUser2->id,
            'nama_kader' => 'Ahmad Fauzi',
            'wilayah'    => 'RT 02 / RW 01',
            'no_hp'      => '081298765432',
        ]);

        $dasawisma2 = Dasawisma::create([
            'kader_id'       => $kader2->id,
            'nama_dasawisma' => 'Dasawisma Mawar',
            'rt'             => '02',
            'rw'             => '01',
            'desa'           => 'Desa Dompas',
        ]);

        // KK 3 - Ditolak
        $kk3 = Keluarga::create([
            'dasawisma_id'         => $dasawisma2->id,
            'no_kk'                => '6208010101000003',
            'nama_kepala_keluarga' => 'Rudi Hermawan',
            'jumlah_anggota'       => 2,
        ]);
        $anggotaKK3 = [
            ['nik' => '6208010101030001', 'nama_anggota' => 'Rudi Hermawan', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1985-04-20', 'agama' => 'Islam', 'pendidikan' => 'SMA/SMK', 'pekerjaan' => 'Nelayan', 'status_dalam_keluarga' => 'Kepala Keluarga', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101030002', 'nama_anggota' => 'Yanti', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1988-07-11', 'agama' => 'Islam', 'pendidikan' => 'SD', 'pekerjaan' => 'Ibu Rumah Tangga', 'status_dalam_keluarga' => 'Istri', 'status_perkawinan' => 'Kawin'],
        ];
        foreach ($anggotaKK3 as $a) $kk3->anggotaKeluargas()->create($a);
        Verifikasi::create(['keluarga_id' => $kk3->id, 'admin_id' => 1, 'tanggal_verifikasi' => '2026-03-22', 'status_verifikasi' => 'ditolak', 'catatan' => 'NIK tidak sesuai format. Harap periksa kembali data NIK anggota.']);

        // KK 4 - Disetujui
        $kk4 = Keluarga::create([
            'dasawisma_id'         => $dasawisma2->id,
            'no_kk'                => '6208010101000004',
            'nama_kepala_keluarga' => 'Suparman',
            'jumlah_anggota'       => 5,
        ]);
        $anggotaKK4 = [
            ['nik' => '6208010101040001', 'nama_anggota' => 'Suparman', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1960-01-15', 'agama' => 'Islam', 'pendidikan' => 'SD', 'pekerjaan' => 'Petani', 'status_dalam_keluarga' => 'Kepala Keluarga', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101040002', 'nama_anggota' => 'Wagini', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1963-09-30', 'agama' => 'Islam', 'pendidikan' => 'SD', 'pekerjaan' => 'Ibu Rumah Tangga', 'status_dalam_keluarga' => 'Istri', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101040003', 'nama_anggota' => 'Bambang Suparman', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1990-03-08', 'agama' => 'Islam', 'pendidikan' => 'SMA/SMK', 'pekerjaan' => 'Swasta', 'status_dalam_keluarga' => 'Anak', 'status_perkawinan' => 'Kawin'],
            ['nik' => '6208010101040004', 'nama_anggota' => 'Rina Suparman', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1993-11-25', 'agama' => 'Islam', 'pendidikan' => 'SMA/SMK', 'pekerjaan' => 'Tidak Bekerja', 'status_dalam_keluarga' => 'Anak', 'status_perkawinan' => 'Belum Kawin'],
            ['nik' => '6208010101040005', 'nama_anggota' => 'Bagas Pratama', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2021-06-12', 'agama' => 'Islam', 'pendidikan' => 'Tidak Sekolah', 'pekerjaan' => 'Tidak Bekerja', 'status_dalam_keluarga' => 'Cucu', 'status_perkawinan' => 'Belum Kawin'],
        ];
        foreach ($anggotaKK4 as $a) $kk4->anggotaKeluargas()->create($a);
        Verifikasi::create(['keluarga_id' => $kk4->id, 'admin_id' => 1, 'tanggal_verifikasi' => '2026-03-23', 'status_verifikasi' => 'disetujui']);
    }
}
