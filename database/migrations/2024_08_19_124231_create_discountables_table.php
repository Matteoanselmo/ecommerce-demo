<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('discountables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->onDelete('cascade');
            $table->morphs('discountable'); // Colonne `discountable_id` e `discountable_type`
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('discountables');
    }
};
