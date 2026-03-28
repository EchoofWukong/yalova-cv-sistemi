<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Çınarcık MYO CV Sistemi</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('cinarcik-logo.jpg') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { zoom: 0.9; background-color: #020617; background-image: radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.7) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(76, 29, 149, 0.6) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.8) 0px, transparent 50%), radial-gradient(at 0% 100%, rgba(55, 48, 163, 0.5) 0px, transparent 50%); background-attachment: fixed; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-wrapper { display: flex; background: white; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); overflow: hidden; max-width: 900px; width: 100%; margin: auto;}
        .login-left { background: linear-gradient(135deg, #9b2c2c, #e53e3e); color: white; padding: 40px; width: 45%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
        .login-left h1 { font-size: 24px; margin-bottom: 15px; font-weight: 900; letter-spacing: 1px; line-height: 1.3; }
        .login-left p { font-size: 15px; opacity: 0.9; line-height: 1.6; }
        
        /* LOGO İÇİN CSS GÜNCELLENDİ (Çerçeve hatası giderildi) */
        .logo-circle { width: 130px; height: 130px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); padding: 5px; overflow: hidden; }
        .logo-circle img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
        
        .login-right { padding: 40px 50px; width: 55%; background: white; display: flex; flex-direction: column; justify-content: center; }
        .login-right h2 { color: #2d3748; font-size: 24px; margin-bottom: 15px; }
        
        /* SEKMELER İÇİN CSS */
        .tab-container { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; }
        .tab-btn { flex: 1; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc; font-weight: bold; color: #4a5568; cursor: pointer; transition: 0.3s; font-size: 14px; }
        .tab-btn.active { border-color: #2d3748; background: #2d3748; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .info-box { background: #ebf8ff; color: #2b6cb0; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 15px; display: none; border-left: 4px solid #3182ce; font-weight: bold; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: bold; color: #4a5568; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; transition: 0.3s; background: #f8fafc; }
        .form-group input:focus, .form-group select:focus { border-color: #e53e3e; background: white; box-shadow: 0 0 0 3px rgba(229,62,62,0.1); }
        .error-msg { color: #e53e3e; font-size: 12px; margin-top: 5px; font-weight: bold; }
        .btn-login { width: 100%; background: #2d3748; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-login:hover { background: #1a202c; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .link-text { text-align: center; margin-top: 20px; }
        .link-text a { color: #4a5568; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s; }
        .link-text a:hover { color: #e53e3e; }

        /* OPTGROUP STİLİ */
        optgroup { font-weight: bold; color: #1e293b; background-color: #f1f5f9; }
        option { font-weight: normal; color: #334155; background-color: #fff; }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-left">
            <div class="logo-circle"><img src="{{ asset('cinarcik-logo.jpg') }}" alt="Çınarcık MYO Logo"></div>
            <h1>YALOVA ÜNİVERSİTESİ<br>ÇINARCIK MYO</h1>
            <p>Yalova Üniversitesi Çınarcık Meslek Yüksekokulu CV ve Yetenek Havuzu Sistemine Hoş Geldiniz.</p>
        </div>

        <div class="login-right">
            <h2>Sisteme Kayıt Ol</h2>
            
            <div class="tab-container">
                <button type="button" id="btn-student" class="tab-btn active" onclick="setRole('student')">🎓 Öğrenci Kaydı</button>
                <button type="button" id="btn-teacher" class="tab-btn" onclick="setRole('teacher')">👨‍🏫 Akademisyen Kaydı</button>
            </div>

            <div id="teacher-info" class="info-box">
                ℹ️ Akademisyen hesapları, güvenlik nedeniyle sistem yöneticisi onayladıktan sonra aktif edilecektir.
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" id="role-input" value="{{ old('role', 'student') }}">
                
                <div class="form-group">
                    <label>Ad Soyad / Unvan</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Örn: Dr. Gökhan Dönmez" required autofocus>
                    @if($errors->has('name'))
                        <div class="error-msg">• {{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Bölüm / Program</label>
                    <select name="department" id="department-select" required>
                        <option value="" disabled selected>Lütfen Bölümünüzü Seçin</option>
                        
                        <option value="Çınarcık MYO (Yönetim)" id="cinarcik-option" style="display:none; font-weight:bold; color:#b91c1c;" {{ old('department') == 'Çınarcık MYO (Yönetim)' ? 'selected' : '' }}>🏛️ Çınarcık MYO (Yönetim - Tüm Yüksekokul)</option>

                        <optgroup label="Büro Hizmetleri ve Sekreterlik Bölümü">
                            <option value="Büro Yönetimi ve Yönetici Asistanlığı Programı" {{ old('department') == 'Büro Yönetimi ve Yönetici Asistanlığı Programı' ? 'selected' : '' }}>Büro Yönetimi ve Yönetici Asistanlığı Programı</option>
                        </optgroup>

                        <optgroup label="Çocuk Bakımı ve Gençlik Hizmetleri Bölümü">
                            <option value="Çocuk Gelişimi Programı" {{ old('department') == 'Çocuk Gelişimi Programı' ? 'selected' : '' }}>Çocuk Gelişimi Programı</option>
                        </optgroup>

                        <optgroup label="Hukuk Bölümü">
                            <option value="Adalet Programı" {{ old('department') == 'Adalet Programı' ? 'selected' : '' }}>Adalet Programı (Yeni öğrenci almamaktadır)</option>
                        </optgroup>

                        <optgroup label="Malzeme ve Malzeme İşleme Teknolojileri Bölümü">
                            <option value="Fiber Tekne İmalatı ve Kompozit Kalıp Teknolojileri Programı" {{ old('department') == 'Fiber Tekne İmalatı ve Kompozit Kalıp Teknolojileri Programı' ? 'selected' : '' }}>Fiber Tekne İmalatı ve Kompozit Kalıp Teknolojileri Programı</option>
                        </optgroup>

                        <optgroup label="Muhasebe ve Vergi Bölümü">
                            <option value="Muhasebe ve Vergi Uygulamaları Programı" {{ old('department') == 'Muhasebe ve Vergi Uygulamaları Programı' ? 'selected' : '' }}>Muhasebe ve Vergi Uygulamaları Programı</option>
                        </optgroup>

                        <optgroup label="Mülkiyet Koruma ve Güvenlik Bölümü">
                            <option value="Özel Güvenlik ve Koruma Programı" {{ old('department') == 'Özel Güvenlik ve Koruma Programı' ? 'selected' : '' }}>Özel Güvenlik ve Koruma Programı</option>
                        </optgroup>

                        <optgroup label="Pazarlama ve Reklamcılık Bölümü">
                            <option value="Medya ve İletişim Programı" {{ old('department') == 'Medya ve İletişim Programı' ? 'selected' : '' }}>Medya ve İletişim Programı</option>
                        </optgroup>

                        <optgroup label="Sosyal Hizmet ve Danışmanlık Bölümü">
                            <option value="Sosyal Hizmetler Programı" {{ old('department') == 'Sosyal Hizmetler Programı' ? 'selected' : '' }}>Sosyal Hizmetler Programı (Yeni öğrenci almamaktadır)</option>
                        </optgroup>

                        <optgroup label="Veri Tabanı, Ağ Tasarımı ve Yönetimi Bölümü">
                            <option value="Kurumsal Bilişim Uzmanlığı Programı" {{ old('department') == 'Kurumsal Bilişim Uzmanlığı Programı' ? 'selected' : '' }}>Kurumsal Bilişim Uzmanlığı Programı</option>
                        </optgroup>

                        <optgroup label="Yönetim ve Organizasyon Bölümü">
                            <option value="Yerel Yönetimler Programı" {{ old('department') == 'Yerel Yönetimler Programı' ? 'selected' : '' }}>Yerel Yönetimler Programı</option>
                        </optgroup>
                    </select>
                    @if($errors->has('department'))
                        <div class="error-msg">• {{ $errors->first('department') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>E-posta Adresi</label>
                    <input type="email" id="email-input" name="email" value="{{ old('email') }}" placeholder="Örn: 252331025@ogrenci.yalova.edu.tr" required>
                    @if($errors->has('email'))
                        <div class="error-msg" style="color: red; font-size: 14px; font-weight: bold; background: #fff5f5; padding: 10px; border-radius: 5px; border-left: 4px solid red; margin-top: 10px;">
                            • {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Şifre Belirle</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @if($errors->has('password'))
                        <div class="error-msg">• {{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Şifreyi Tekrar Gir</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login" id="submit-btn">ÖĞRENCİ OLARAK KAYIT OL</button>

                <div class="link-text">
                    <a href="{{ route('login') }}">Zaten hesabın var mı? Giriş Yap</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // SAYFA YÜKLENDİĞİNDE HAFIZADAKİ ROLÜ OTOMATİK SEÇ
        window.onload = function() {
            let activeRole = document.getElementById('role-input').value;
            setRole(activeRole);
        };

        function setRole(role) {
            document.getElementById('role-input').value = role;
            let emailInput = document.getElementById('email-input');
            let submitBtn = document.getElementById('submit-btn');
            let cinarcikOption = document.getElementById('cinarcik-option');
            let departmentSelect = document.getElementById('department-select');
            
            if(role === 'student') {
                document.getElementById('btn-student').classList.add('active');
                document.getElementById('btn-teacher').classList.remove('active');
                emailInput.placeholder = "Örn: 252331025@ogrenci.yalova.edu.tr";
                emailInput.pattern = ".+@ogrenci\\.yalova\\.edu\\.tr";
                emailInput.title = "Lütfen @ogrenci.yalova.edu.tr uzantılı e-postanızı girin.";
                document.getElementById('teacher-info').style.display = 'none';
                submitBtn.innerText = "ÖĞRENCİ OLARAK KAYIT OL";
                submitBtn.style.background = "#2d3748";
                
                // Öğrenci Yönetim seçemesin
                cinarcikOption.style.display = 'none';
                if(departmentSelect.value === 'Çınarcık MYO (Yönetim)') {
                    departmentSelect.value = '';
                }
            } else {
                document.getElementById('btn-teacher').classList.add('active');
                document.getElementById('btn-student').classList.remove('active');
                emailInput.placeholder = "Örn: hocanizinmaili@gmail.com";
                emailInput.removeAttribute('pattern');
                emailInput.title = "Geçerli bir e-posta adresi girin.";
                document.getElementById('teacher-info').style.display = 'block';
                submitBtn.innerText = "AKADEMİSYEN OLARAK ONAYA GÖNDER";
                submitBtn.style.background = "#9b2c2c";
                
                // Hoca Yönetimi seçebilsin
                cinarcikOption.style.display = 'block';
            }
        }
    </script>
</body>
</html>