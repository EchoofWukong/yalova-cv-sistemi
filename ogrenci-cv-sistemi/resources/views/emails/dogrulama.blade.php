<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .email-container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 40px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .logo { width: 120px; margin-bottom: 25px; }
        h2 { color: #1a202c; font-size: 22px; margin-bottom: 15px; }
        p { color: #4a5568; line-height: 1.6; font-size: 15px; text-align: justify; margin-bottom: 20px; }
        .btn { display: inline-block; background: #e53e3e; color: white !important; text-decoration: none; padding: 15px 30px; border-radius: 8px; font-weight: bold; margin: 15px 0 25px 0; font-size: 16px; box-shadow: 0 4px 6px rgba(229, 62, 62, 0.2); }
        .footer { margin-top: 35px; font-size: 13px; color: #718096; border-top: 2px solid #edf2f7; padding-top: 20px; text-align: left; line-height: 1.5; }
        .link-text { word-break: break-all; color: #2b6cb0; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="email-container">
        <img src="{{ asset('yalova-logo.png') }}" alt="Yalova Logo" class="logo">
        
        <h2>Merhaba Değerli Öğrencimiz,</h2>
        
        <p>Yalova Üniversitesi Çınarcık MYO Akıllı Özgeçmiş (CV) Oluşturma platformuna kayıt olduğunuz için teşekkür ederiz. Sisteme güvenli bir şekilde giriş yapabilmek ve profesyonel CV'nizi oluşturmaya başlamak için lütfen aşağıdaki butona tıklayarak e-posta adresinizi doğrulayın.</p>
        
        <a href="{{ $url }}" class="btn">E-posta Adresimi Doğrula</a>
        
        <p>Eğer bu hesabı siz oluşturmadıysanız, herhangi bir işlem yapmanıza gerek yoktur. Güvenle bu e-postayı silebilirsiniz.</p>
        
        <p style="text-align: center; margin-top: 30px;">Başarılar Dileriz,<br><strong style="color: #1a202c; font-size: 16px;">Yalova Üniversitesi Kariyer Merkezi</strong></p>

        <div class="footer">
            <strong>Not:</strong> Eğer üstteki "E-posta Adresimi Doğrula" butonuna tıklamakta sorun yaşıyorsanız, aşağıdaki bağlantıyı kopyalayıp web tarayıcınızın adres çubuğuna yapıştırabilirsiniz:<br><br>
            <a href="{{ $url }}" class="link-text">{{ $url }}</a>
        </div>
    </div>
</body>
</html>