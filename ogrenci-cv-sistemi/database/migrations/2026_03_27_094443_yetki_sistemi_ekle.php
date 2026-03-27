<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // student (öğrenci) veya teacher (öğretmen) rollerini tutacak
            $table->string('role')->default('student'); 
            
            // Öğrenciler anında sisteme girer (true), ama öğretmenler sen onaylayana kadar (false) bekler
            $table->boolean('is_approved')->default(true); 
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_approved']);
        });
    }
};