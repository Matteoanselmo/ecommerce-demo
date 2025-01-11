<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relazione con l'utente
            $table->unsignedBigInteger('shipping_address_id'); // Relazione con user_addresses
            $table->unsignedBigInteger('billing_address_id'); // Relazione con billing_addresses
            $table->string('status'); // Stato dell'ordine
            $table->date('order_date'); // Data dell'ordine
            $table->string('shipping_number')->nullable(); // Numero di spedizione
            $table->string('order_number')->unique(); // Numero univoco dell'ordine
            $table->integer('total_amount'); // Totale dell'ordine

            // Nuovi campi aggiunti
            $table->date('data')->nullable(); // Data generica
            $table->date('shipped_in')->nullable(); // Data di spedizione
            $table->string('payment')->nullable(); // Metodo di pagamento
            $table->string('details')->nullable(); // Dettagli dell'ordine
            $table->string('fattura')->nullable(); // Percorso o nome del file della fattura

            $table->timestamps();

            // Chiavi esterne
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            $table->foreign('billing_address_id')->references('id')->on('billing_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
}
