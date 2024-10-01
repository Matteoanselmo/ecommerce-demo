<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Associa il ticket a un utente
            $table->string('product')->nullable(); // Capi associati al ticket
            $table->string('subject'); // Oggetto del ticket
            $table->text('description'); // Descrizione del problema
            $table->enum('status', ['Aperto', 'In Attesa', 'Chiuso'])->default('Aperto'); // Stato del ticket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('support_tickets');
    }
};
