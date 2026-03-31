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
        'nik',
        'nama_anggota',
        'jenis_kelamin',
        'tanggal_lahir',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_dalam_keluarga',
        'status_perkawinan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }
}
