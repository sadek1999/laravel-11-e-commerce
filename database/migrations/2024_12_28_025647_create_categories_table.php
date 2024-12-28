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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Category name
            $table->foreignId('department_id') // Foreign key to departments table
                ->constrained()
                ->cascadeOnDelete(); // Automatically delete categories when a department is deleted
            $table->foreignId('parent_id') // Self-referencing foreign key for subcategories
                ->nullable() // Allow null for top-level categories
                ->constrained('categories') // References the same table
                ->nullOnDelete(); // Set parent_id to null if the parent is deleted
            $table->boolean('active')->default(true); // Active status
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

