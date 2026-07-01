<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Keluarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'dasawisma_id', 'no_kk', 'nama_kepala_keluarga', 'jumlah_anggota',
        'rt', 'rw', 'dusun_lingkungan', 'desa', 'kecamatan', 'kabupaten', 'provinsi',
        'jumlah_kk', 'jumlah_balita', 'jumlah_pus', 'jumlah_wus', 'jumlah_buta', 
        'jumlah_ibu_hamil', 'jumlah_ibu_menyusui', 'jumlah_lansia',
        'jumlah_laki_laki', 'jumlah_perempuan', 'jumlah_balita_laki', 'jumlah_balita_perempuan',
        'jumlah_berkebutuhan_khusus', 'sehat_layak_huni', 'memiliki_tempat_sampah',
        'memiliki_spal', 'menempel_stiker_p4k', 'sumber_air', 'makanan_pokok',
        'ikut_up2k', 'ikut_pekarangan', 'ikut_industri', 'ikut_kerja_bakti'
    ];

    public function dasawisma(): BelongsTo
    {
        return $this->belongsTo(Dasawisma::class);
    }

    public function anggotaKeluargas(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function verifikasi(): HasOne
    {
        return $this->hasOne(Verifikasi::class);
    }
}
