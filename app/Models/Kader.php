<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kader extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama_kader', 'wilayah', 'no_hp'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dasawismas(): HasMany
    {
        return $this->hasMany(Dasawisma::class);
    }
}
