<?php

use App\Models\Magazin;
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
            $table->string('title');
            $table->longText('description');
            $table->integer('views')->default(0);
            $table->float('price');
            $table->float('oldprice');
            // $table->string('image');
            $table->json('images'); // Store array of images as JSON
            $table->foreignIdFor(Magazin::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('category_id'); 
            $table->unsignedBigInteger('subcategory_id'); 
            $table->timestamps();
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
