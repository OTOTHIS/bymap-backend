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
        Schema::create('operations', function (Blueprint $table) {
            $table->integer('id');
            $table->enum('type', ['+', '-']);
            $table->integer('qte');
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->unsignedBigInteger('magazin_id'); // Foreign key to magazins table
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('magazin_id')->references('id')->on('magazins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation');
    }
};
