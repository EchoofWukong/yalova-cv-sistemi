<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    
    // Güvenlik duvarını esnetiyoruz (toplu veri kaydetmek için)
    protected $guarded = [];

    // Bir CV, bir Kullanıcıya aittir
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Bir CV'nin BİRDEN FAZLA eğitimi olabilir (İşte sihir burada)
    public function egitimler() {
        return $this->hasMany(Egitim::class);
    }

    // Bir CV'nin BİRDEN FAZLA deneyimi olabilir
    public function deneyimler() {
        return $this->hasMany(Deneyim::class);
    }
}