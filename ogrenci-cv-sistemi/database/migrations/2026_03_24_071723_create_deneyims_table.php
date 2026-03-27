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
        Schema::create('deneyims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained()->onDelete('cascade');
            
            $table->string('sirket_adi');
            $table->string('pozisyon');
            $table->string('baslangic_tarihi')->nullable();
            $table->string('bitis_tarihi')->nullable();
            $table->text('aciklama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deneyims');
    }
};
