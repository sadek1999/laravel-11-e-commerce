<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id') // Correct column name
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
        });

        Schema::create('variation_type_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_type_id') // Correct column name
                ->index()
                ->constrained('variation_types')
                ->cascadeOnDelete();
            $table->string('name');
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id') // Correct column name
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->json('products_variation_options');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 20, 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
