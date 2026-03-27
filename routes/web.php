<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

// ANA SAYFAYI LOGINE YÖNLENDİR
Route::redirect('/', '/login');

// =========================================================================
// AKADEMİSYEN ONAYLAMA ROTASI (SENİN İSTEDİĞİN KRİTİK KISIM)
// =========================================================================
Route::get('/admin/akademisyen-onayla/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);
    
    // Hesabı onaylıyoruz
    $user->is_approved = true;
    $user->save(); 
    
    return "
    <div style='font-family:sans-serif; text-align:center; margin-top:50px; padding:20px;'>
        <div style='display:inline-block; border:1px solid #ddd; padding:40px; border-radius:15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
            <h2 style='color: #27ae60;'>✅ İşlem Başarılı!</h2>
            <p style='font-size:18px;'><b>{$user->name}</b> isimli akademisyen onaylandı.</p>
            <p>Artık sisteme giriş yapabilir.</p>
            <br>
            <a href='".url('/login')."' style='padding:12px 25px; background:#2c3e50; color:white; text-decoration:none; border-radius:5px; font-weight:bold;'>Sisteme Git</a>
        </div>
    </div>";
})->name('admin.teacher.approve')->middleware('signed');

// =========================================================================
// GİRİŞ YAPMIŞ KULLANICI ROTALARI
// =========================================================================

// Dashboard (Giriş yapınca açılan yer)
Route::get('/anasayfa', function () {
    $cvler = Cv::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    return view('dashboard', compact('cvler'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Hakkında Sayfası
Route::get('/hakkinda', function () {
    return view('hakkinda');
})->middleware(['auth', 'verified'])->name('hakkinda');

// Gruplandırılmış Rotalar
Route::middleware('auth')->group(function () {
    
    // CV Sistemi Rotaları
    Route::get('/cv/olustur', [CvController::class, 'create'])->name('cv.create');
    Route::post('/cv/kaydet', [CvController::class, 'store'])->name('cv.store');
    
    // Arama ve Görüntüleme
    Route::get('/ogrenci-ara', [CvController::class, 'search'])->name('cv.search');
    Route::get('/cv/goster/{id}', [CvController::class, 'show'])->name('cv.show');
    
    // Düzenleme ve Silme
    Route::get('/cv/duzenle/{id}', [CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv/guncelle/{id}', [CvController::class, 'update'])->name('cv.update');
    Route::delete('/cv/sil/{id}', [CvController::class, 'destroy'])->name('cv.delete');

    // Profil Ayarları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth dosyalarını dahil et (Login, Register vb.)
require __DIR__.'/auth.php';

// =========================================================================
// RENDER İÇİN ARKA KAPI (CACHE TEMİZLEME + VERİTABANI KURULUMU)
// =========================================================================
Route::get('/veritabanini-kur', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        $dbConnection = config('database.default');
        $dbName = config('database.connections.' . $dbConnection . '.database');
        
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        return "
        <div style='font-family:sans-serif; text-align:center; margin-top:50px;'>
            <h2 style='color:green;'>✅ Hafıza Temizlendi ve Tablolar Kuruldu!</h2>
            <p style='font-size: 18px;'><b>Bağlanılan Sistem:</b> <span style='color:blue;'>{$dbConnection}</span></p>
            <p style='font-size: 18px;'><b>Veritabanı Adı:</b> <span style='color:blue;'>{$dbName}</span></p>
            <p>Eğer yukarıda <b>'pgsql'</b> yazıyorsa sorun kesin çözüldü demektir. DBeaver'a gidip Refresh atabilirsin.</p>
        </div>";
    } catch (\Exception $e) {
        return '<h2 style="color:red; text-align:center; margin-top:50px;">❌ Bir hata oluştu: ' . $e->getMessage() . '</h2>';
    }
});