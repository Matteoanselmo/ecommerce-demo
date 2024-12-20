<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingAddressesTable extends Migration {
    public function up() {
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nome privato o denominazione azienda
            $table->string('tax_id')->nullable(); // Partita IVA o Codice Fiscale
            $table->string('address'); // Indirizzo
            $table->string('house_number')->nullable(); // Numero civico
            $table->string('postal_code'); // CAP
            $table->string('city'); // Città
            $table->string('state'); // Provincia
            $table->string('country'); // Paese
            $table->string('phone_number')->nullable(); // Telefono
            $table->boolean('is_primary')->default(false); // Indica l'indirizzo principale
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('billing_addresses');
    }
}
