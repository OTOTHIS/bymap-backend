<?php


use App\Models\Owner;
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
        //DD102
        Schema::create('magazins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('Latitude');
            $table->string('Longitude');
            $table->text('image');
            $table->unsignedBigInteger('owner_id');
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magazins');
    }
};
