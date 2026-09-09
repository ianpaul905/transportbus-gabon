<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('ville_depart');
            $table->string('ville_arrivee');
            $table->date('date_depart');
            $table->time('heure_depart');
            $table->decimal('prix', 10, 2);
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};