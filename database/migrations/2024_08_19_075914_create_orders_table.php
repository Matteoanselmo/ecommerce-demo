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
            $table->unsignedBigInteger('user_id'); // Colonna per la relazione con l'utente
            $table->string('status'); // Stato dell'ordine
            $table->date('order_date'); // Data dell'ordine
            $table->string('shipping_number')->nullable(); // Numero di spedizione
            $table->string('order_number')->unique(); // Numero dell'ordine univoco
            $table->integer('total_amount'); // Totale dell'ordine

            // Nuovi campi aggiunti
            $table->date('data')->nullable(); // Data generica senza orario
            $table->date('shipped_in')->nullable(); // Data di spedizione
            $table->string('payment')->nullable(); // Metodo di pagamento
            $table->string('details')->nullable(); // Dettagli dell'ordine
            $table->string('fattura')->nullable(); // Percorso o nome del file della fattura

            $table->timestamps();

            // Chiave esterna per la relazione con la tabella users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
