<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail; // Bunu zaten eklemiştik
use Illuminate\Support\Facades\URL;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Gelen Rolün Geçerli Olup Olmadığını Kontrol Et
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:student,teacher'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 2. Rol Öğrenciyse Okul Maili Zorunlu, Öğretmense Serbest
        if ($request->role === 'student') {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@ogrenci.yalova.edu.tr'],
            ], ['email.ends_with' => 'Sisteme sadece @ogrenci.yalova.edu.tr uzantılı okul e-postanız ile kayıt olabilirsiniz.']);
            $is_approved = true; // Öğrenci anında onaylanır
        } else {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            ]);
            $is_approved = false; // Öğretmen onay bekler
        }

        
       // 3. Kullanıcıyı Veritabanına Kaydet
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $is_approved,
            // EĞER ÖĞRETMENSE: E-posta onay tarihini ŞİMDİ olarak ayarla (Böylece o ekranı görmez)
            'email_verified_at' => ($request->role === 'teacher') ? now() : null,
        ]);

       // 4. EĞER ÖĞRETMENSE: Sana mail at (Onay Butonlu)
        if ($request->role === 'teacher') {
            // Güvenli, süreli ve imzalı onay linki oluşturuyoruz
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
                    <p style='font-size: 12px; color: #718096; margin-top: 20px;'>Not: Bu bağlantı sadece size özeldir ve güvenlidir.</p>
                </div>";

            Mail::html($mesaj, function ($msg) use ($adminEmail) {
                $msg->to($adminEmail)->subject('🚨 Akademisyen Onay Talebi - Yalova CV');
            });

            return redirect()->route('login')->with('status', 'Kayıt talebiniz yöneticiye iletildi. Onaylandıktan sonra giriş yapabilirsiniz.');
        }

        // 5. ÖĞRENCİYSE: Doğrulama maili gönder ve giriş yapıp sisteme al
        event(new \Illuminate\Auth\Events\Registered($user));
        \Illuminate\Support\Facades\Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
