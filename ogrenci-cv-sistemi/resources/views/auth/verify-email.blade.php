<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-posta Doğrulama - Yalova Üniversitesi CV Sistemi</title>
    <link rel="icon" type="image/png" href="{{ asset('yalova-logo.png') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f0f4f8; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .verify-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 550px; width: 100%; text-align: center; }
        .logo { width: 90px; margin-bottom: 20px; }
        h2 { color: #2d3748; margin-bottom: 15px; font-size: 24px; font-weight: 900; }
        p { color: #4a5568; line-height: 1.6; font-size: 15px; margin-bottom: 25px; }
        .btn { background: #2d3748; color: white; border: none; padding: 14px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; width: 100%; margin-bottom: 15px; font-size: 14px; }
        .btn:hover { background: #1a202c; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .btn-logout { background: #e53e3e; color: white; text-decoration: none; border: none; padding: 14px 20px; border-radius: 8px; display: inline-block; width: 100%; font-weight: bold; font-size: 14px; transition: 0.3s; cursor: pointer;}
        .btn-logout:hover { background: #c53030; }
        .success-msg { color: #22543d; font-weight: bold; margin-bottom: 20px; font-size: 14px; background: #c6f6d5; padding: 15px; border-radius: 8px; border-left: 5px solid #38a169;}
    </style>
</head>
<body>
    <div class="verify-box">
        <img src="{{ asset('yalova-logo.png') }}" alt="Yalova Üniversitesi" class="logo">
        <h2>E-posta Adresinizi Doğrulayın</h2>
        <p>Sisteme kayıt olduğunuz için teşekkürler! Özgeçmiş oluşturmaya başlamadan önce, size gönderdiğimiz e-postadaki bağlantıya tıklayarak adresinizi doğrulamanız gerekmektedir. Eğer e-postayı almadıysanız, aşağıdaki butona tıklayarak yeni bir tane isteyebilirsiniz.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="success-msg">
                ✅ Yeni bir doğrulama bağlantısı e-posta adresinize başarıyla gönderildi! (Spam klasörünü kontrol etmeyi unutmayın.)
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">DOĞRULAMA E-POSTASINI TEKRAR GÖNDER</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Sistemden Çıkış Yap</button>
        </form>
    </div>
</body>
</html>