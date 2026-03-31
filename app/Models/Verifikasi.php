<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Verifikasi extends Model
{
    use HasFactory;

    protected $fillable = ['keluarga_id', 'admin_id', 'tanggal_verifikasi', 'status_verifikasi', 'catatan'];

    protected $casts = [
        'tanggal_verifikasi' => 'date',
    ];

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
