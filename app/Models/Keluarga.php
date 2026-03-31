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

    protected $fillable = ['dasawisma_id', 'no_kk', 'nama_kepala_keluarga', 'jumlah_anggota'];

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
