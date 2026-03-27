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
        Schema::create('egitims', function (Blueprint $table) {
            $table->id();
            // Hangi CV'ye ait olduğunu bağlıyoruz. CV silinirse eğitim de silinir (cascade)
            $table->foreignId('cv_id')->constrained()->onDelete('cascade');
            
            $table->string('okul_adi');
            $table->string('bolum');
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
        Schema::dropIfExists('egitims');
    }
};
