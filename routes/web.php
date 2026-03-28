<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
// --- SÜPER ADMİN ROTALARI ---
    Route::get('/yonetici-paneli', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/yonetici-paneli/onayla/{id}', [AdminController::class, 'approveTeacher'])->name('admin.approve');
    Route::delete('/yonetici-paneli/sil/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete');
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
// RENDER ARKA KAPI (HER ŞEYİ SİL VE BAŞTAN KUR)
// =========================================================================
Route::get('/veritabanini-kur', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        // DİKKAT: migrate:fresh tüm tabloları siler ve dosyaya bakarak sıfırdan kurar!
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        
        return "<h2 style='color:green; text-align:center; margin-top:50px;'>✅ Eski her şey silindi, 'department' sütunu ile beraber tablolar tertemiz kuruldu!</h2>";
    } catch (\Exception $e) {
        return '<h2 style="color:red; text-align:center; margin-top:50px;">❌ Bir hata oluştu: ' . $e->getMessage() . '</h2>';
    }
});
