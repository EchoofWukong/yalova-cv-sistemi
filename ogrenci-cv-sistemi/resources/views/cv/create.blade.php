<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('yalova-logo.png') }}">
    <title>Yeni CV Oluştur</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7f6; padding: 40px 20px; color: #333; }
        .form-container { background: #fff; max-width: 950px; margin: 0 auto; padding: 35px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #4a5568; text-decoration: none; font-size: 14px; font-weight: bold; background: #edf2f7; padding: 8px 15px; border-radius: 6px; }
        h2 { border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 25px; color: #1a202c; display: flex; align-items: center; gap: 10px; font-size: 24px; }
        h3 { margin: 30px 0 15px 0; color: #2d3748; border-left: 5px solid #4a5568; padding-left: 12px; font-size: 18px; background: #f8fafc; padding: 8px 0; display: flex; align-items: center; gap: 8px;}
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 5px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 13px; color: #4a5568; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; background: #fff; }
        input[type="file"] { padding: 9px; cursor: pointer; background: #f8fafc; width: 100%; border: 1px solid #cbd5e0; border-radius: 8px;}
        input[type="color"] { width: 100%; height: 45px; border: none; border-radius: 8px; cursor: pointer; padding: 0; }
        .full-width { grid-column: span 2; }
        .btn-submit { background: #38a169; color: white; border: none; padding: 18px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 35px; transition: 0.3s; }
        .btn-submit:hover { background: #2f855a; transform: translateY(-2px); }
        body { zoom: 0.9; }
        .dynamic-box { border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; background: #fbfbfc; position: relative; margin-bottom: 15px; }
        .btn-add { background: #edf2f7; color: #2b6cb0; border: 1px solid #cbd5e0; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: bold; transition: 0.2s; }
        .btn-remove { position: absolute; top: 15px; right: 15px; background: #fed7d7; color: #c53030; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer; font-size: 12px; font-weight: bold; }
        
        .template-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 20px; }
        .template-option { border: 2px solid #e2e8f0; border-radius: 10px; padding: 15px; text-align: center; cursor: pointer; transition: 0.2s; height: 100%; }
        input[type="radio"]:checked + .template-option { border-color: #e53e3e; background: #fff5f5; }
        .template-option h4 { color: #2d3748; margin-bottom: 5px; font-size: 14px; }
        .template-option p { font-size: 11px; color: #718096; }

        #renk-ayarlari { display: none; background: #fff5f5; border: 1px dashed #fc8181; padding: 20px; border-radius: 8px; margin-top: 15px; transition: 0.3s; }
    </style>
</head>
<body>

    <div class="form-container">
        <a href="{{ route('dashboard') }}" class="btn-back">⬅ Ana Sayfaya Dön</a>
        <h2><span class="icon-plus">➕</span> Yeni CV Oluştur</h2>
        
        <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3>🎨 CV Şablonu Seç</h3>
            <div class="template-grid">
                <label>
                    <input type="radio" name="sablon" value="klasik" class="sablon-radio" style="display: none;" checked>
                    <div class="template-option">
                        <h4>Klasik</h4>
                        <p>Resmi kurumlar için alt alta.</p>
                    </div>
                </label>
                <label>
                    <input type="radio" name="sablon" value="minimalist" class="sablon-radio" style="display: none;">
                    <div class="template-option">
                        <h4>Minimalist</h4>
                        <p>Fotoğrafsız, dikey mavi çizgili sade.</p>
                    </div>
                </label>
                <label>
                    <input type="radio" name="sablon" value="modern" class="sablon-radio" style="display: none;">
                    <div class="template-option">
                        <h4>Modern</h4>
                        <p>Sarı vurgulu ve şık görünüm.</p>
                    </div>
                </label>
                <label>
                    <input type="radio" name="sablon" value="kurumsal" class="sablon-radio" style="display: none;">
                    <div class="template-option">
                        <h4>Kurumsal</h4>
                        <p>Kendi renklerini seçebildiğin şablon.</p>
                    </div>
                </label>
                <label>
                    <input type="radio" name="sablon" value="yaratici" class="sablon-radio" style="display: none;">
                    <div class="template-option">
                        <h4>Yaratıcı</h4>
                        <p>Dinamik ve renkli tasarım.</p>
                    </div>
                </label>
            </div>

            <div id="renk-ayarlari">
                <h4 style="color: #c53030; margin-bottom: 15px;">🖌️ Kurumsal Şablona Özel Renklerini Seç</h4>
                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="form-group"><label>Ana Renk (Menü Rengi)</label><input type="color" name="ana_renk" value="#1e3a8a"></div>
                    <div class="form-group"><label>Yan Renk (Detaylar)</label><input type="color" name="yan_renk" value="#dbeafe"></div>
                    <div class="form-group"><label>Yazı Rengi (Menü Üstü)</label><input type="color" name="yazi_rengi" value="#ffffff"></div>
                </div>
            </div>

            <h3>👤 Kişisel Bilgiler</h3>
            <div class="grid">
                <div class="form-group"><label>Ad Soyad</label><input type="text" name="ad_soyad" required></div>
                <div class="form-group"><label>E-posta</label><input type="email" name="eposta" required></div>
                <div class="form-group"><label>Telefon</label><input type="text" name="telefon"></div>
                <div class="form-group"><label>LinkedIn</label><input type="text" name="linkedin"></div>
                <div class="form-group full-width"><label>Adres</label><input type="text" name="adres"></div>
                
                <div id="foto-alani" class="form-group full-width" style="background: #ebf8ff; padding: 15px; border-radius: 8px;">
                    <label>📸 Profil Fotoğrafı</label>
                    <input type="file" name="profil_fotografi" accept="image/*">
                </div>

                <div class="form-group full-width"><label>Beceriler (Virgülle Ayırın)</label><input type="text" name="yetenekler"></div>
                <div class="form-group full-width"><label>Hakkında</label><textarea name="hakkimda" rows="3"></textarea></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
                <h3>🎓 Eğitim Bilgileri</h3>
                <button type="button" class="btn-add" onclick="egitimEkle()">➕ Yeni Eğitim Ekle</button>
            </div>
            <div id="egitim-alani"></div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
                <h3>💼 İş ve Staj Deneyimleri</h3>
                <button type="button" class="btn-add" onclick="deneyimEkle()">➕ Yeni Deneyim Ekle</button>
            </div>
            <div id="deneyim-alani"></div>

            <button type="submit" class="btn-submit">💾 KAYDET VE DEVAM ET</button>
        </form>
    </div>

    <script>
        document.querySelectorAll('.sablon-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                // Renk ayarları göster/gizle
                if(this.value === 'kurumsal') {
                    document.getElementById('renk-ayarlari').style.display = 'block';
                } else {
                    document.getElementById('renk-ayarlari').style.display = 'none';
                }

                // Minimalist seçilirse fotoğraf alanını gizle
                if(this.value === 'minimalist') {
                    document.getElementById('foto-alani').style.display = 'none';
                } else {
                    document.getElementById('foto-alani').style.display = 'block';
                }
            });
        });

        // Sayfa yüklendiğinde mevcut duruma göre çalıştır
        let secili = document.querySelector('input[name="sablon"]:checked').value;
        if(secili === 'kurumsal') document.getElementById('renk-ayarlari').style.display = 'block';
        if(secili === 'minimalist') document.getElementById('foto-alani').style.display = 'none';

        function egitimEkle() {
            document.getElementById('egitim-alani').insertAdjacentHTML('beforeend', `<div class="dynamic-box"><button type="button" class="btn-remove" onclick="this.parentElement.remove()">❌ Sil</button><div class="grid"><div class="form-group"><label>Okul Adı</label><input type="text" name="egitim_okul[]"></div><div class="form-group"><label>Bölüm</label><input type="text" name="egitim_bolum[]"></div><div class="form-group"><label>Başlangıç Tarihi</label><input type="text" name="egitim_bas[]"></div><div class="form-group"><label>Bitiş Tarihi</label><input type="text" name="egitim_bitis[]"></div></div></div>`);
        }
        function deneyimEkle() {
            document.getElementById('deneyim-alani').insertAdjacentHTML('beforeend', `<div class="dynamic-box"><button type="button" class="btn-remove" onclick="this.parentElement.remove()">❌ Sil</button><div class="grid"><div class="form-group"><label>Şirket Adı</label><input type="text" name="deneyim_sirket[]"></div><div class="form-group"><label>Pozisyon</label><input type="text" name="deneyim_pozisyon[]"></div><div class="form-group"><label>Başlangıç</label><input type="text" name="deneyim_bas[]"></div><div class="form-group"><label>Bitiş</label><input type="text" name="deneyim_bitis[]"></div><div class="form-group full-width"><label>Açıklama</label><textarea name="deneyim_aciklama[]" rows="2"></textarea></div></div></div>`);
        }
        
        egitimEkle(); deneyimEkle();
    </script>
</body>
</html>