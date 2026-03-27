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
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('sablon')->default('modern'); 
            
            // YENİ EKLENEN RENK SÜTUNLARI
            $table->string('ana_renk')->default('#4A5568');
            $table->string('yan_renk')->default('#edf2f7');
            $table->string('yazi_rengi')->default('#ffffff');

            $table->string('ad_soyad')->nullable();
            $table->string('eposta')->nullable();
            $table->string('telefon')->nullable();
            $table->string('adres')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('hakkimda')->nullable();
            $table->text('yetenekler')->nullable();
            $table->string('profil_fotografi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
