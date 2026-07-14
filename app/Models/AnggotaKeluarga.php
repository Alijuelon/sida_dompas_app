<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'keluarga_id',
        'no_reg',
        'nik',
        'nama_anggota',
        'jenis_kelamin',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_dalam_keluarga',
        'status_perkawinan',
        'dasa_wisma',
        'nama_kepala_rumah_tangga',
        'jabatan',
        'tempat_lahir',
        'umur',
        'alamat_jalan',
        'rt',
        'rw',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'pendidikan_terakhir',
        'pekerjaan_utama',
        'akseptor_kb',
        'jenis_akseptor_kb',
        'aktif_posyandu',
        'frekuensi_posyandu',
        'ikut_bina_keluarga_balita',
        'memiliki_tabungan',
        'ikut_kelompok_belajar',
        'jenis_paket_belajar',
        'ikut_paud_sejenis',
        'ikut_koperasi',
        'jenis_koperasi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'akseptor_kb' => 'boolean',
        'aktif_posyandu' => 'boolean',
        'ikut_bina_keluarga_balita' => 'boolean',
        'memiliki_tabungan' => 'boolean',
        'ikut_kelompok_belajar' => 'boolean',
        'ikut_paud_sejenis' => 'boolean',
        'ikut_koperasi' => 'boolean',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }
}
