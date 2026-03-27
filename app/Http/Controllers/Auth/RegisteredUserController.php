<?php

namespace App\Http\Controllers\Auth; // TEK NAMESPACE OLMALI!

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Temel Doğrulama
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:student,teacher'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Rol Bazlı E-posta Doğrulaması (HATA MESAJLARI ÖZELLEŞTİRİLDİ)
        if ($request->role === 'student') {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@ogrenci.yalova.edu.tr'],
            ], [
                'email.ends_with' => 'Öğrenci olarak sadece @ogrenci.yalova.edu.tr uzantılı mail ile kayıt olabilirsiniz.',
                'email.unique' => 'Bu öğrenci e-postası zaten sistemde kayıtlı.'
            ]);
            $is_approved = true;
        } else {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            ], [
                'email.unique' => 'Bu akademisyen e-postası zaten sistemde kayıtlı. Lütfen giriş yapmayı deneyin.'
            ]);
            $is_approved = false;
        }

        // 3. Kullanıcıyı Veritabanına Kaydet
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $is_approved,
            'email_verified_at' => ($request->role === 'teacher') ? now() : null,
        ]);

        // 4. ÖĞRETMENSE: Admin'e onay maili at
        if ($request->role === 'teacher') {
            $approveUrl = URL::signedRoute('admin.teacher.approve', ['id' => $user->id]);
            $adminEmail = 'yalova.cv.sistemi@gmail.com';

            $mesaj = "
                <div style='font-family: sans-serif; padding: 30px; border: 1px solid #edf2f7; border-radius: 15px; max-width: 500px;'>
                    <h2 style='color: #9b2c2c; margin-bottom: 20px;'>🚨 Yeni Akademisyen Kaydı!</h2>
                    <p>Sistemde onayınızı bekleyen yeni bir akademisyen var:</p>
                    <div style='background: #f8fafc; padding: 15px; border-radius: 10px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>Ad Soyad:</strong> {$user->name}</p>
                        <p style='margin: 5px 0;'><strong>E-Posta:</strong> {$user->email}</p>
                    </div>
                    <p>Bu kişinin sisteme erişmesini istiyorsanız aşağıdaki butona tıklayın:</p>
                    <a href='{$approveUrl}' style='display: inline-block; background: #2d3748; color: white; padding: 15px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 10px;'>AKADEMİSYENİ ONAYLA</a>
                </div>";

            // HATA YAKALAYICI (Burası hayat kurtaracak)
            try {
                Mail::html($mesaj, function ($msg) use ($adminEmail) {
                    $msg->to($adminEmail)->subject('🚨 Akademisyen Onay Talebi - Yalova CV');
                });
            } catch (\Exception $e) {
                // Eğer şifre veya port yanlışsa kırmızı ekran vermek yerine formda hatayı gösterecek!
                return back()->withInput()->withErrors(['email' => 'Kayıt başarılı ama mail gönderilemedi. HATA: ' . $e->getMessage()]);
            }

            return redirect()->route('login')->with('status', 'Kayıt talebiniz yöneticiye iletildi. Onaylandıktan sonra giriş yapabilirsiniz.');
        }

        // 5. ÖĞRENCİYSE: Doğrulama maili gönder
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}