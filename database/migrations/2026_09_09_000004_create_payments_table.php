<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_transaction')->unique();
            $table->string('mode_paiement');
            $table->decimal('montant', 10, 2);
            $table->string('statut')->default('en_attente');
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->string('telephone');
            $table->string('provider_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};