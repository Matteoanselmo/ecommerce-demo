<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome dello sconto
            $table->decimal('discount_value', 5, 2); // Percentuale o importo fisso dello sconto
            $table->enum('discount_type', ['percentage', 'fixed']); // Tipo di sconto (percentuale o fisso)
            $table->date('start_date'); // Data di inizio dello sconto
            $table->date('end_date'); // Data di fine dello sconto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('discounts');
    }
};
