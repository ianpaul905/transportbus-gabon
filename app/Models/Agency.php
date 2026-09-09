<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    protected $fillable = ['nom', 'ville', 'adresse', 'telephone'];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }
}