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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->decimal('price');
            $table->string('thumbnail');
            $table->string('brand');
            $table->decimal('discount');
            $table->bigInteger('stock');
            $table->integer('rating');
            $table->timestamps();

            // Referencia a las Categorías
            $table->foreignId('categoryid')
                ->constrained('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
