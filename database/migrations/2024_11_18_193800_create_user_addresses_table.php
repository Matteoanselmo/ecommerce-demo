<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relazione con la tabella users
            $table->string('recipient_name'); // Destinatario
            $table->string('company_name')->nullable(); // Presso
            $table->string('address'); // Indirizzo
            $table->string('house_number'); // Numero Civico
            $table->string('postal_code'); // CAP
            $table->string('country'); // Nazione
            $table->string('state'); // Provincia
            $table->string('city'); // Città
            $table->string('phone_number'); // Telefono
            $table->boolean('is_primary')->default(false); // Indica l'indirizzo principale
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('user_addresses');
    }
};
