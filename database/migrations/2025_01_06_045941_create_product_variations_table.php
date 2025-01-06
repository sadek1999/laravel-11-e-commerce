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
        Schema::create('variation_Types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
        });
        Schema::create('variation_Type_Options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('VariationTypeId')
                ->index()
                ->constrained('variation_Types')
                ->cascadeOnDelete();
            $table->string('name');
        });
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')
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
