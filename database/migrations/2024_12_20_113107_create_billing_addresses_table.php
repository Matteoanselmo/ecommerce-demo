<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingAddressesTable extends Migration {
    public function up() {
        Schema::create('billing_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['private', 'company']); // Tipo di dati: privato o azienda

            // Campi comuni
            $table->string('address'); // Indirizzo
            $table->string('internal')->nullable(); // Interno
            $table->string('city'); // Città
            $table->string('postal_code'); // CAP
            $table->string('state')->nullable(); // Provincia
            $table->string('country'); // Paese
            $table->string('tax_code')->nullable(); // Codice Fiscale (privato e azienda)

            // Campi per i privati
            $table->string('first_name')->nullable(); // Nome (solo privato)
            $table->string('last_name')->nullable(); // Cognome (solo privato)

            // Campi per le aziende
            $table->string('company_name')->nullable(); // Ragione Sociale (solo azienda)
            $table->string('vat_number')->nullable(); // Partita IVA (solo azienda)
            $table->string('sdi_code')->nullable(); // Codice SDI (solo azienda)

            $table->string('phone_number')->nullable(); // Telefono
            $table->boolean('is_primary')->default(false); // Indica l'indirizzo principale
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('billing_addresses');
    }
}
