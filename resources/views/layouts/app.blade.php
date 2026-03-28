@php
    // JSON DOSYASINI OKU VEYA YOKSA OTOMATİK OLUŞTUR
    $ayarDosyasi = storage_path('app/ayarlar.json');
    
    if (!file_exists($ayarDosyasi)) {
        $varsayilanAyarlar = [
            'sistem_adi' => 'Yalova Üniversitesi CV Sistemi',
            'hakkimizda_kisa' => 'Öğrencilerimizi profesyonel iş dünyasına bir adım önde hazırlamak için geliştirilmiş akıllı özgeçmiş (CV) yönetim sistemidir.',
            'adres' => 'Yalova Üniversitesi Merkez Kampüsü, Çınarcık Yolu',
            'email' => 'yalova.cv.sistemi@gmail.com',
            'telefon' => '0226 815 50 00',
            'gelistirici' => 'Gökhan Onmaz'
        ];
        // Dosyayı Türkçe karakter uyumlu ve düzenli (PRETTY_PRINT) formatta kaydet
        file_put_contents($ayarDosyasi, json_encode($varsayilanAyarlar, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    // Ayarları $ayarlar değişkenine yükle
    $ayarlar = json_decode(file_get_contents($ayarDosyasi));
@endphp

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ayarlar->sistem_adi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('yalova-logo.png') }}">
    <style>
        body { zoom: 0.9; font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        
        .header-nav { background: white; padding: 10px 60px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .nav-left { display: flex; align-items: center; gap: 30px; }
        .nav-left img { height: 50px; }
        .nav-links { display: flex; gap: 25px; }
        .nav-links a { text-decoration: none; color: #4a5568; font-weight: 700; font-size: 14px; text-transform: uppercase; transition: 0.3s; }
        .nav-links a:hover { color: #1e3a8a; }
        
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .user-label { font-weight: 800; color: #2d3748; font-size: 13px; letter-spacing: 0.5px; }
        
        .btn-logout { 
            background: #fff; border: 1px solid #e2e8f0; color: #4a5568; 
            padding: 8px 15px; border-radius: 10px; cursor: pointer; 
            font-weight: bold; transition: 0.3s; font-size: 12px;
            display: flex; align-items: center; gap: 6px;
        }
        .btn-logout:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }
        .btn-logout svg { width: 14px; height: 14px; stroke: currentColor; stroke-width: 2; fill: none; }

        .site-footer { background: #1e3a8a; color: white; margin-top: auto; padding: 40px 60px 20px 60px; font-size: 14px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 30px; margin-bottom: 20px; }
        .footer-col h4 { margin-bottom: 15px; font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #dbeafe; }
        .footer-col p { margin-bottom: 10px; color: #bfdbfe; line-height: 1.6; font-size: 13px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a { color: #bfdbfe; text-decoration: none; transition: 0.3s; font-size: 13px;}
        .footer-links a:hover { color: white; padding-left: 5px; }
        .footer-bottom { text-align: center; color: #93c5fd; font-size: 13px; }
    </style>
</head>
<body>
    <header class="header-nav">
        <div class="nav-left">
            <a href="{{ route('dashboard') }}"><img src="{{ asset('yalova-logo.png') }}" alt="Yalova Logo"></a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Ana Sayfa</a>
                <a href="{{ route('hakkinda') }}">Hakkında</a>
            </div>
        </div>
        <div class="nav-right">
            @auth
            <span class="user-label">
                {{ mb_strtoupper(Auth::user()->name) }} 
<span style="font-size: 12px; opacity: 0.8;">
    (
    @if(Auth::user()->role === 'admin')
        👑 SÜPER ADMİN
    @elseif(Auth::user()->role === 'teacher')
        👨‍🏫 AKADEMİSYEN
    @else
        🎓 ÖĞRENCİ
    @endif
    )
</span>
            </span>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">
                    Çıkış Yap
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
            @endauth
        </div>
    </header>

    <main style="flex: 1; padding: 30px;">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Yalova Üniversitesi</h4>
                <p>{{ $ayarlar->hakkimizda_kisa }}</p>
            </div>
            <div class="footer-col">
                <h4>Hızlı Bağlantılar</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('dashboard') }}">Ana Sayfa</a></li>
                    <li><a href="{{ route('hakkinda') }}">Sistem Hakkında</a></li>
                    <li><a href="#">Kullanım Kılavuzu</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>İletişim</h4>
                <p>📍 {{ $ayarlar->adres }}</p>
                <p>✉️ {{ $ayarlar->email }}</p>
                <p>📞 {{ $ayarlar->telefon }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} {{ $ayarlar->sistem_adi }} - Geliştirici: {{ $ayarlar->gelistirici }}. Tüm Hakları Saklıdır.
        </div>
    </footer>
</body>
</html>