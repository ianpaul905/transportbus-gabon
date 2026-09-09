<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('client_nom');
            $table->string('client_telephone');
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee', 'utilisee'])->default('en_attente');
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('qr_code')->nullable();
            $table->integer('nombre_places')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};