<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Admin Panelini Gösterir
    public function index()
    {
        // Güvenlik: Sadece 'admin' rolü olanlar girebilir!
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Sadece sistem yöneticileri bu sayfaya erişebilir.');
        }

        // Verileri Çek
        $bekleyenHocalar = User::where('role', 'teacher')->where('is_approved', false)->get();
        $tumKullanicilar = User::whereIn('role', ['student', 'teacher'])->orderBy('created_at', 'desc')->get();

        // İstatistikler
        $istatistikler = [
            'ogrenci_sayisi' => User::where('role', 'student')->count(),
            'hoca_sayisi' => User::where('role', 'teacher')->count(),
            'onay_bekleyen' => $bekleyenHocalar->count(),
        ];

        return view('admin.dashboard', compact('bekleyenHocalar', 'tumKullanicilar', 'istatistikler'));
    }

    // Hocayı Onayla VE E-postasını Otomatik Doğrula
    public function approveTeacher($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $user = User::findOrFail($id);
        
        // 1. Hocayı Onayla
        $user->is_approved = true;
        
        // 2. SENİN İSTEĞİN: Eğer e-postası doğrulanmamışsa, şu anki zamanı basarak doğrulanmış say!
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
        }

        $user->save();

        return back()->with('success', $user->name . ' adlı akademisyenin hesabı onaylandı ve e-postası otomatik doğrulandı!');
    }

    // Kullanıcıyı Sil (Şutla)
    public function deleteUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Kullanıcı sistemden başarıyla silindi!');
    }
}