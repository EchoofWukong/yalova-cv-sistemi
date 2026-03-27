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
        // Gelen verileri doğrula
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher'],
        ]);

        // AKADEMİSYEN (teacher) İSE: Onay bekliyor (false), ÖĞRENCİ (student) İSE: Direkt aktif (true)
        $is_approved = ($request->role === 'teacher') ? false : true;

        // 1. Kullanıcıyı Veritabanına Kaydet
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $is_approved,
        ]);

        // 2. AKADEMİSYEN İSE: Yöneticiye (Sana) Onay Maili Gönder (BREVO API ÜZERİNDEN)
        if ($request->role === 'teacher') {
            
            // Senin istediğin o Gmail adresi
            $adminEmail = 'yalova.cv.sistemi@gmail.com'; 
            
            // Güvenli (Signed) Onay Linki Oluştur
            // NOT: 'admin.teacher.approve' rotasının web.php'de tanımlı olması gerekir!
            $onayLink = URL::signedRoute('admin.teacher.approve', ['id' => $user->id]);

            $mesaj = "
                <div style='font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                    <h2 style='color: #2c3e50;'>🚨 Akademisyen Onay Talebi</h2>
                    <p>Sisteme yeni bir hoca kayıt oldu. Onayınızı bekliyor:</p>
                    <hr>
                    <p><b>Hoca Adı:</b> {$user->name}</p>
                    <p><b>E-posta:</b> {$user->email}</p>
                    <hr>
                    <p>Bu hocanın sisteme giriş yapabilmesi için aşağıdaki butona tıklayarak onay verin:</p>
                    <div style='text-align: center; margin-top: 20px;'>
                        <a href='{$onayLink}' style='padding:12px 25px; background-color: #27ae60; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>HOCAYI ONAYLA</a>
                    </div>
                </div>
            ";

            try {
                // BREVO API TRANSPORTU BURADA DEVREYE GİRER
                Mail::html($mesaj, function ($msg) use ($adminEmail, $user) {
                    $msg->to($adminEmail)
                        ->subject('🚨 Yeni Akademisyen Kaydı: ' . $user->name);
                });
            } catch (\Exception $e) {
                // Mail gitmese bile hatayı logla, sistem çökmesin
                \Log::error("Akademisyen mail hatası: " . $e->getMessage());
            }

            return redirect()->route('login')->with('status', 'Akademisyen kaydınız alındı. Yönetici onayından sonra giriş yapabilirsiniz.');
        }

        // 3. ÖĞRENCİ İSE: Standart Doğrulama Mailini (Brevo) Fırlat!
        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Öğrenci kaydınız başarılı! Lütfen e-postanıza gelen doğrulama linkine tıklayın.');
    }
}