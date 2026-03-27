<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;

Route::redirect('/', '/login');

// GİRİŞ YAPINCA AÇILAN PANEL
Route::get('/anasayfa', function () {
    $cvler = Cv::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    return view('dashboard', compact('cvler'));
})->middleware(['auth', 'verified'])->name('dashboard');

// HAKKINDA SAYFASI
Route::get('/hakkinda', function () {
    return view('hakkinda');
})->middleware(['auth', 'verified'])->name('hakkinda');

// SADECE GİRİŞ YAPMIŞ KİŞİLERİN GÖREBİLECEĞİ ROTALAR
Route::middleware('auth')->group(function () {
    
    // CV Sistemi Rotaları (TEKİLLEŞTİRİLDİ - ÇAKIŞMALAR SİLİNDİ)
    Route::get('/cv/olustur', [CvController::class, 'create'])->name('cv.create');
    Route::post('/cv/kaydet', [CvController::class, 'store'])->name('cv.store');
    
    // ARAMA VE GÖRÜNTÜLEME
    Route::get('/ogrenci-ara', [CvController::class, 'search'])->name('cv.search');
    Route::get('/cv/goster/{id}', [CvController::class, 'show'])->name('cv.show');
    
    // DÜZENLEME VE SİLME
    Route::get('/cv/duzenle/{id}', [CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv/guncelle/{id}', [CvController::class, 'update'])->name('cv.update');
    Route::delete('/cv/sil/{id}', [CvController::class, 'destroy'])->name('cv.delete');

    // Profil Rotaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\URL;

// AKADEMİSYEN ONAYLAMA ROTASI
Route::get('/admin/akademisyen-onayla/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);
    $user->update(['is_approved' => true]);
    
    return "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>
                <h2 style='color: #2d3748;'>✅ İşlem Başarılı!</h2>
                <p><b>{$user->name}</b> isimli akademisyen onaylandı. Artık sisteme giriş yapabilir.</p>
                <a href='".url('/login')."'>Sisteme Git</a>
            </div>";
})->name('admin.teacher.approve')->middleware('signed');