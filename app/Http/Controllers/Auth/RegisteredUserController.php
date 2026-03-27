<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Kayıt sayfasını gösterir.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Kayıt işlemini gerçekleştirir.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher'],
        ]);

        // AKADEMİSYEN İSE: Onay bekliyor (false), ÖĞRENCİ İSE: Direkt aktif (true)
        $is_approved = ($request->role === 'teacher') ? false : true;

        // 1. Kullanıcıyı Veritabanına Kaydet
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $is_approved,
        ]);

        // 2. AKADEMİSYEN İSE: Yöneticiye (Sana) Onay Maili Gönder
        if ($request->role === 'teacher') {
            $adminEmail = 'gkhann213@gmail.com'; // Buraya kendi mailini yaz kanka
            $onayLink = URL::signedRoute('admin.teacher.approve', ['id' => $user->id]);

            $mesaj = "
                <h2>Yeni Akademisyen Kaydı Mevcut!</h2>
                <p><b>İsim:</b> {$user->name}</p>
                <p><b>E-posta:</b> {$user->email}</p>
                <p>Bu hesabı onaylamak için aşağıdaki butona tıklayın:</p>
                <a href='{$onayLink}' style='padding:10px 20px; background:green; color:white; text-decoration:none; border-radius:5px;'>HESABI ONAYLA</a>
            ";

            try {
                Mail::html($mesaj, function ($msg) use ($adminEmail) {
                    $msg->to($adminEmail)->subject('🚨 Akademisyen Onay Talebi - Yalova CV');
                });
            } catch (\Exception $e) {
                // Hata olsa bile kullanıcıyı login sayfasına atalım
            }

            return redirect()->route('login')->with('status', 'Akademisyen kaydınız alındı. Yönetici onayından sonra giriş yapabilirsiniz.');
        }

        // 3. ÖĞRENCİ İSE: Doğrulama Mailini (Brevo Üzerinden) Fırlat!
        event(new Registered($user));

        // Öğrenciyi hemen içeri alma, önce mailini onaylasın
        return redirect()->route('login')->with('status', 'Öğrenci kaydınız başarılı! Lütfen e-postanıza gelen doğrulama linkine tıklayın.');
    }
}