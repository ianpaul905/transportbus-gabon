<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'ville_depart',
        'ville_arrivee',
        'date_depart',
        'heure_depart',
        'prix',
        'bus_id',
    ];

    protected $casts = [
        'date_depart' => 'date',
        'prix' => 'decimal:2',
    ];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function placesConfirmees(): int
    {
        return (int) $this->reservations()
            ->whereIn('statut', ['confirmee', 'utilisee'])
            ->sum('nombre_places');
    }

    public function placesRestantes(): int
    {
        return max(0, $this->bus->nombre_places - $this->placesConfirmees());
    }

    public function estComplet(): bool
    {
        return $this->placesRestantes() <= 0;
    }
}