<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Çınarcık MYO CV Sistemi</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('cinarcik-logo.jpg') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* %90 BOYUT VE GECE MAVİSİ BLURLU (MESH) ARKA PLAN */
        body {
            zoom: 0.9;
            background-color: #020617; /* Gece Siyahı / Koyu Lacivert Zemin */
            background-image:
                radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.7) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(76, 29, 149, 0.6) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.8) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(55, 48, 163, 0.5) 0px, transparent 50%);
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* YUMUŞAK AÇILIŞ ANİMASYONU (FADE UP) */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-wrapper { 
            display: flex; 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.4); 
            overflow: hidden; 
            max-width: 900px; 
            width: 100%; 
            margin: auto; 
            animation: fadeUp 0.7s ease-out; /* Animasyon buraya eklendi */
        }
        
        /* Sol Taraf - Karşılama */
        .login-left { background: linear-gradient(135deg, #9b2c2c, #e53e3e); color: white; padding: 50px; width: 50%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        .login-left h1 { font-size: 26px; margin-bottom: 15px; font-weight: 900; letter-spacing: 1px; line-height: 1.3; }
        .login-left p { font-size: 15px; opacity: 0.9; line-height: 1.6; }
        
        /* LOGO İÇİN KUSURSUZ CSS (Taşma engellendi, boyut büyüdü, Hover animasyonu eklendi) */
        .logo-circle { 
            width: 145px; /* Daha büyük yapıldı */
            height: 145px; 
            background: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-bottom: 25px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
            padding: 0; /* Padding SIFIRLANDI ki taşma yapmasın */
            overflow: hidden; /* Taşan her şeyi jilet gibi keser */
            border: 4px solid white; /* Etrafına ekstra temiz bir sınır */
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .logo-circle:hover {
            transform: scale(1.08) rotate(3deg); /* Üzerine gelince tatlı bir büyüme ve hafif dönme */
        }
        .logo-circle img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; /* Resmi tam sığdırır */
        }

        /* Sağ Taraf - Form */
        .login-right { padding: 50px; width: 50%; background: white; display: flex; flex-direction: column; justify-content: center; }
        .login-right h2 { color: #2d3748; font-size: 24px; margin-bottom: 30px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; color: #4a5568; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; transition: color 0.3s; }
        .form-group input { width: 100%; padding: 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; outline: none; transition: all 0.3s; background: #f8fafc; }
        .form-group input:focus { border-color: #e53e3e; background: white; box-shadow: 0 0 0 4px rgba(229,62,62,0.1); transform: translateY(-2px); }
        .form-group input:focus + label { color: #e53e3e; } /* Seçilince yazının rengi değişsin */
        
        .error-msg { color: #e53e3e; font-size: 13px; margin-top: 5px; font-weight: bold; animation: fadeUp 0.3s; }
        
        .btn-login { width: 100%; background: #2d3748; color: white; border: none; padding: 16px; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; transition: all 0.3s; margin-top: 10px; position: relative; overflow: hidden; }
        .btn-login:hover { background: #1a202c; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        .btn-login:active { transform: translateY(0); }
        
        /* Şifremi Unuttum Linki Hover Efekti */
        .forgot-pass { color: #e53e3e; text-decoration: none; font-size: 13px; font-weight: bold; transition: 0.2s; }
        .forgot-pass:hover { color: #c53030; text-decoration: underline; }

        @media (max-width: 768px) {
            .login-wrapper { flex-direction: column; }
            .login-left, .login-right { width: 100%; }
            .login-left { padding: 40px 20px; }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-left">
            <div class="logo-circle">
                <img src="{{ asset('cinarcik-logo.jpg') }}" alt="Çınarcık MYO Logo">
            </div>
            <h1>YALOVA ÜNİVERSİTESİ<br>ÇINARCIK MYO</h1>
            <p>Yalova Üniversitesi Çınarcık Meslek Yüksekokulu CV ve Yetenek Havuzu Sistemine Hoş Geldiniz.</p>
        </div>

        <div class="login-right">
            <h2>Sisteme Giriş</h2>
            
            @if (session('status'))
                @php
                    $durum = session('status');
                    if($durum == 'passwords.reset' || str_contains($durum, 'reset')) {
                        $durum = 'Şifreniz başarıyla sıfırlandı! Artık yeni şifrenizle giriş yapabilirsiniz.';
                    }
                @endphp
                <div style="background: #c6f6d5; color: #22543d; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 13px; border-left: 5px solid #38a169; animation: fadeUp 0.5s ease-out;">
                    ✅ {{ $durum }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>E-posta Adresi</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Sisteme kayıtlı e-posta adresiniz" required autofocus>
                    @if($errors->has('email'))
                        <div class="error-msg">• Girdiğiniz e-posta hatalı veya sisteme kayıtlı değil.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Şifre</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @if($errors->has('password'))
                        <div class="error-msg">• Girdiğiniz şifre hatalı. Lütfen tekrar deneyin.</div>
                    @endif
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <label style="font-size: 14px; color: #4a5568; cursor: pointer; display: flex; align-items: center; gap: 8px; margin-bottom: 0;">
                        <input type="checkbox" name="remember" style="width: 18px; height: 18px; cursor: pointer;"> Beni Hatırla
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-pass">
                            Şifremi Unuttum?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">SİSTEME GİRİŞ YAP</button>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('register') }}" style="color: #4a5568; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s;">Hesabın yok mu? Yeni Kayıt</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>