<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum - Çınarcık MYO</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('cinarcik-logo.jpg') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { zoom: 0.9; background-color: #020617; background-image: radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.7) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(76, 29, 149, 0.6) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.8) 0px, transparent 50%), radial-gradient(at 0% 100%, rgba(55, 48, 163, 0.5) 0px, transparent 50%); background-attachment: fixed; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-wrapper { display: flex; background: white; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); overflow: hidden; max-width: 900px; width: 100%; margin: auto; }
        .login-left { background: linear-gradient(135deg, #9b2c2c, #e53e3e); color: white; padding: 50px; width: 50%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        .login-left h1 { font-size: 24px; margin-bottom: 15px; font-weight: 900; letter-spacing: 1px; line-height: 1.3; }
        .logo-circle { width: 130px; height: 130px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); padding: 5px; overflow: hidden;}
        .logo-circle img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
        .login-right { padding: 50px; width: 50%; background: white; display: flex; flex-direction: column; justify-content: center; }
        .login-right h2 { color: #2d3748; font-size: 24px; margin-bottom: 15px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; color: #4a5568; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input { width: 100%; padding: 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px; outline: none; transition: 0.3s; background: #f8fafc; }
        .form-group input:focus { border-color: #e53e3e; background: white; box-shadow: 0 0 0 3px rgba(229,62,62,0.1); }
        .error-msg { color: #e53e3e; font-size: 13px; margin-top: 5px; font-weight: bold; }
        .btn-login { width: 100%; background: #2d3748; color: white; border: none; padding: 16px; border-radius: 10px; font-size: 15px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-login:hover { background: #1a202c; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .info-text { font-size: 14px; color: #718096; margin-bottom: 25px; line-height: 1.5; }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-left">
            <div class="logo-circle">
                <img src="{{ asset('cinarcik-logo.jpg') }}" alt="Çınarcık MYO Logo">
            </div>
            <h1>YALOVA ÜNİVERSİTESİ<br>ÇINARCIK MYO</h1>
            <p>Parolanızı mı unuttunuz? Sorun değil, hemen sıfırlayalım.</p>
        </div>

        <div class="login-right">
            <h2>🔑 Şifremi Unuttum</h2>
            
            <p class="info-text">
                Kayıtlı e-posta adresinizi girin. Size şifrenizi sıfırlamanız için güvenli bir bağlantı göndereceğiz.
            </p>

            @if (session('status'))
                <div style="background: #c6f6d5; color: #22543d; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 13px; border-left: 5px solid #38a169;">
                    ✉️ {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label>E-posta Adresiniz</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Sisteme kayıtlı e-posta adresiniz" required autofocus>
                    @if($errors->has('email'))
                        <div class="error-msg">• {{ $errors->first('email') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn-login">ŞİFRE SIFIRLAMA LİNKİ GÖNDER</button>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('login') }}" style="color: #4a5568; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s;">⬅ Giriş Ekranına Dön</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>