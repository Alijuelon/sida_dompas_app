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
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ========================
        // 1. AKUN ADMIN
        // ========================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sidadompas.id'],
            [
                'name'              => 'Administrator',
                'username'          => 'admin',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'status'            => true,
                'email_verified_at' => now(),
            ]
        );

        Admin::firstOrCreate(
            ['user_id' => $adminUser->id],
            ['nama_admin' => 'Administrator SIDA']
        );

        // ========================
        // 2. AKUN KEPALA DESA
        // ========================
        User::firstOrCreate(
            ['email' => 'kades@sidadompas.id'],
            [
                'name'              => 'Bpk. Suryadi',
                'username'          => 'kades1',
                'password'          => Hash::make('password'),
                'role'              => 'kades',
                'status'            => true,
                'email_verified_at' => now(),
            ]
        );

        // ========================
        // 3. AKUN KADER 1 & 2
        // ========================
        $kadersData = [
            [
                'name' => 'Ibu Siti Aminah',
                'username' => 'kader1',
                'email' => 'kader1@sidadompas.id',
                'wilayah' => 'RT 01 / RW 01',
                'no_hp' => '081234567890',
                'rt' => '01'
            ]
        ];

        $kaders = [];
        foreach ($kadersData as $index => $kd) {
            $user = User::firstOrCreate(
                ['email' => $kd['email']],
                [
                    'name'              => $kd['name'],
                    'username'          => $kd['username'],
                    'password'          => Hash::make('password'),
                    'role'              => 'kader',
                    'status'            => true,
                    'email_verified_at' => now(),
                ]
            );

            $kader = Kader::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_kader' => $kd['name'],
                    'wilayah'    => $kd['wilayah'],
                    'no_hp'      => $kd['no_hp'],
                ]
            );
            $kaders[] = ['model' => $kader, 'rt' => $kd['rt']];
        }

        // ========================
        // 4. GENERATE DUMMY DATA (Dasawisma, Keluarga, Anggota)
        // ========================
        
        $agamas = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $pendidikanTerakhirs = ['Tidak Tamat SD', 'SD/MI', 'SMP', 'SMU/SMK', 'Diploma', 'S1', 'S2', 'S3'];
        $pendidikanUmums = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'];
        $pekerjaanUtamas = ['Petani', 'Nelayan', 'PNS', 'Pegawai Swasta', 'Wiraswasta', 'Ibu Rumah Tangga', 'Pelajar/Mahasiswa', 'Tidak Bekerja'];
        $pekerjaanSelects = ['Tidak Bekerja', 'Petani', 'Nelayan', 'Pedagang', 'PNS', 'Swasta', 'Wiraswasta', 'TNI/Polri', 'Pelajar/Mahasiswa', 'Ibu Rumah Tangga', 'Lainnya'];
        
        // Status lengkap untuk anggota ke-3 dst (simulasi ditambah via Detail KK)
        $statusDetailKK = ['Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Lainnya'];
        
        foreach ($kaders as $kData) {
            $kader = $kData['model'];
            $rt = $kData['rt'];
            
            // Buat 1 Dasawisma per Kader
            for ($d = 1; $d <= 1; $d++) {
                $namaDasawisma = 'Dasawisma Mawar 1';
                $dasawisma = Dasawisma::firstOrCreate(
                    ['nama_dasawisma' => $namaDasawisma, 'kader_id' => $kader->id],
                    [
                        'rt'   => $rt,
                        'rw'   => '01',
                        'desa' => 'Desa Dompas',
                    ]
                );

                // Buat 10 Keluarga per Dasawisma
                for ($k = 1; $k <= 10; $k++) {
                    $kepalaKeluargaName = $faker->name('male');
                    $noKk = $faker->numerify('140801##########'); // Format Riau, Bengkalis
                    $jumlahAnggota = $faker->numberBetween(3, 6);
                    
                    $keluarga = Keluarga::create([
                        'dasawisma_id'         => $dasawisma->id,
                        'no_kk'                => $noKk,
                        'nama_kepala_keluarga' => $kepalaKeluargaName,
                        'jumlah_anggota'       => $jumlahAnggota,
                        'rt'                   => $rt,
                        'rw'                   => '01',
                        'dusun_lingkungan'     => $faker->randomElement(['Murni', 'Lestari']),
                        'desa'                 => 'Dompas',
                        'kecamatan'            => 'Bukit Batu',
                        'kabupaten'            => 'Bengkalis',
                        'provinsi'             => 'Riau',
                        // Rekap fields dummy
                        'jumlah_kk'            => 1,
                        'jumlah_balita'        => $faker->numberBetween(0, 2),
                        'jumlah_pus'           => 1,
                        'jumlah_wus'           => 1,
                        'jumlah_buta'          => 0,
                        'jumlah_ibu_hamil'     => $faker->numberBetween(0, 1),
                        'jumlah_ibu_menyusui'  => $faker->numberBetween(0, 1),
                        'jumlah_lansia'        => $faker->numberBetween(0, 2),
                        // Kriteria Rumah lengkap
                        'sehat_layak_huni'           => $faker->boolean(80),
                        'memiliki_tempat_sampah'     => $faker->boolean(90),
                        'memiliki_spal'              => $faker->boolean(70),
                        'memiliki_jamban'            => $faker->boolean(85),
                        'menempel_stiker_p4k'        => $faker->boolean(60),
                        'sumber_air'                 => $faker->randomElement(['PDAM', 'Sumur', 'Lainnya']),
                        'makanan_pokok'              => $faker->randomElement(['Beras', 'Non Beras']),
                        'ikut_up2k'                  => $faker->boolean(50),
                        'ikut_pekarangan'            => $faker->boolean(60),
                        'ikut_industri'              => $faker->boolean(30),
                        'ikut_kerja_bakti'           => $faker->boolean(90),
                    ]);

                    // Generate status verifikasi acak
                    $statuses = ['disetujui', 'menunggu', 'ditolak'];
                    $status = $statuses[array_rand($statuses)];
                    
                    Verifikasi::create([
                        'keluarga_id'        => $keluarga->id,
                        'admin_id'           => $status != 'menunggu' ? $adminUser->id : null,
                        'tanggal_verifikasi' => $status != 'menunggu' ? Carbon::now()->subDays(rand(1, 30)) : null,
                        'status_verifikasi'  => $status,
                        'catatan'            => $status == 'ditolak' ? 'Terdapat ketidaksesuaian data NIK' : null,
                    ]);

                    // Generate Anggota Keluarga
                    for ($a = 1; $a <= $jumlahAnggota; $a++) {
                        $isKK = ($a === 1);
                        $isIstri = ($a === 2);
                        $gender = $isKK ? 'L' : ($isIstri ? 'P' : $faker->randomElement(['L', 'P']));
                        $nama = $isKK ? $kepalaKeluargaName : $faker->name($gender == 'L' ? 'male' : 'female');
                        $tglLahir = $isKK
                            ? $faker->dateTimeBetween('-60 years', '-25 years')
                            : ($isIstri
                                ? $faker->dateTimeBetween('-55 years', '-20 years')
                                : $faker->dateTimeBetween('-20 years', '-1 years'));
                        
                        // Status dalam keluarga:
                        // - Anggota ke-1: Kepala Keluarga (konsisten dengan Create.vue)
                        // - Anggota ke-2: Istri (ditambahkan via Detail KK, status detail)
                        // - Anggota ke-3+: Anak, Menantu, Cucu dll (ditambahkan via Detail KK)
                        if ($isKK) {
                            $statusDlmKeluarga = 'Kepala Keluarga';
                        } elseif ($isIstri) {
                            $statusDlmKeluarga = 'Istri';
                        } else {
                            // Simulasi variasi status yang ditambahkan via Detail KK
                            $statusDlmKeluarga = $faker->randomElement($statusDetailKK);
                        }
                        
                        $statusKawin = ($isKK || $isIstri) ? 'Kawin' : ($faker->boolean(20) ? 'Kawin' : 'Belum Kawin');
                        
                        // Pekerjaan (select) & pekerjaan utama (text detail)
                        if ($isKK) {
                            $pekerjaan = $faker->randomElement(['Petani', 'Nelayan', 'PNS', 'Swasta', 'Wiraswasta', 'Pedagang']);
                            $pekerjaanUtama = $pekerjaan;
                        } elseif ($isIstri) {
                            $pekerjaan = 'Ibu Rumah Tangga';
                            $pekerjaanUtama = 'Ibu Rumah Tangga';
                        } else {
                            $pekerjaan = $faker->randomElement(['Pelajar/Mahasiswa', 'Tidak Bekerja', 'Swasta', 'Wiraswasta']);
                            $pekerjaanUtama = $pekerjaan === 'Pelajar/Mahasiswa' ? 'Pelajar' : $pekerjaan;
                        }
                        
                        // Pendidikan
                        $pendidikan = $faker->randomElement($pendidikanUmums);
                        
                        $akseptorKb = $gender == 'P' && $isIstri ? $faker->boolean(60) : false;
                        $aktifPosyandu = $gender == 'P' && $isIstri ? $faker->boolean(70) : false;
                        $ikutBelajar = $faker->boolean(20);
                        $ikutKoperasi = ($isKK || $isIstri) ? $faker->boolean(40) : false;

                        AnggotaKeluarga::create([
                            'keluarga_id'              => $keluarga->id,
                            'no_reg'                   => $faker->numerify('REG-####-####'),
                            'nik'                      => $faker->numerify('140801##########'),
                            'nama_anggota'             => $nama,
                            'jenis_kelamin'            => $gender,
                            'tanggal_lahir'            => $tglLahir,
                            'tempat_lahir'             => $faker->city(),
                            'umur'                     => Carbon::parse($tglLahir)->age,
                            'agama'                    => 'Islam',
                            'pendidikan'               => $pendidikan,
                            'pekerjaan'                => $pekerjaan,
                            'status_dalam_keluarga'    => $statusDlmKeluarga,
                            'status_perkawinan'        => $statusKawin,
                            'pendidikan_terakhir'      => $faker->randomElement($pendidikanTerakhirs),
                            'pekerjaan_utama'          => $pekerjaanUtama,
                            'jabatan'                  => $isKK ? 'Anggota' : '',
                            
                            // Data Wilayah Individu (sinkron dengan keluarga/dasawisma)
                            'dasa_wisma'               => $namaDasawisma,
                            'nama_kepala_rumah_tangga' => $kepalaKeluargaName,
                            'alamat_jalan'             => 'Jl. Jenderal Sudirman No. ' . $faker->buildingNumber(),
                            'rt'                       => $rt,
                            'rw'                       => '01',
                            'desa_kelurahan'           => 'Dompas',
                            'kecamatan'                => 'Bukit Batu',
                            'kabupaten_kota'           => 'Bengkalis',
                            'provinsi'                 => 'Riau',
                            
                            // Data Khusus PKK
                            'akseptor_kb'              => $akseptorKb,
                            'jenis_akseptor_kb'        => $akseptorKb ? $faker->randomElement(['Pil', 'Suntik', 'IUD', 'Implan']) : null,
                            'aktif_posyandu'           => $aktifPosyandu,
                            'frekuensi_posyandu'       => $aktifPosyandu ? $faker->numberBetween(1, 12) . ' kali/tahun' : null,
                            'ikut_bina_keluarga_balita'=> $faker->boolean(30),
                            'memiliki_tabungan'        => $faker->boolean(80),
                            'ikut_kelompok_belajar'    => $ikutBelajar,
                            'jenis_paket_belajar'      => $ikutBelajar ? $faker->randomElement(['Paket A', 'Paket B', 'Paket C']) : null,
                            'ikut_paud_sejenis'        => $faker->boolean(10),
                            'ikut_koperasi'            => $ikutKoperasi,
                            'jenis_koperasi'           => $ikutKoperasi ? 'Simpan Pinjam' : null,
                        ]);
                    }
                    
                    // Hitung jumlah laki-laki dan perempuan, update keluarga
                    $jumlahL = $keluarga->anggotaKeluargas()->where('jenis_kelamin', 'L')->count();
                    $jumlahP = $keluarga->anggotaKeluargas()->where('jenis_kelamin', 'P')->count();
                    $jumlahBalitaL = $keluarga->anggotaKeluargas()->where('jenis_kelamin', 'L')->where('umur', '<=', 5)->count();
                    $jumlahBalitaP = $keluarga->anggotaKeluargas()->where('jenis_kelamin', 'P')->where('umur', '<=', 5)->count();

                    $keluarga->update([
                        'jumlah_laki_laki' => $jumlahL,
                        'jumlah_perempuan' => $jumlahP,
                        'jumlah_balita_laki' => $jumlahBalitaL,
                        'jumlah_balita_perempuan' => $jumlahBalitaP,
                    ]);
                }
            }
        }
    }
}
