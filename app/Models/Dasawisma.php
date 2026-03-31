<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dasawisma extends Model
{
    use HasFactory;

    protected $fillable = ['kader_id', 'nama_dasawisma', 'rt', 'rw', 'desa'];

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class);
    }

    public function keluargas(): HasMany
    {
        return $this->hasMany(Keluarga::class);
    }
}
