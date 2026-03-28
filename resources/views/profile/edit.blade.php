@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; padding: 0 20px; zoom: 0.95;">
    
    <a href="{{ route('dashboard') }}" style="display: inline-block; margin-bottom: 20px; color: #4a5568; text-decoration: none; font-size: 15px; font-weight: bold; background: #edf2f7; padding: 10px 20px; border-radius: 8px; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        ⬅ Ana Sayfaya Dön
    </a>

    <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 35px; border-radius: 20px; color: white; margin-bottom: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 10px 0; font-size: 26px;">⚙️ Hesap Ayarları</h2>
        <p style="margin: 0; color: #94a3b8; font-size: 15px;">Profil bilgilerinizi, e-posta adresinizi ve şifrenizi buradan güvenle güncelleyebilirsiniz.</p>
    </div>

    <div style="background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 30px; border: 1px solid #f1f5f9;">
        <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px;">👤 Profil Bilgileri</h3>
        
        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #475569; margin-bottom: 8px;">Ad Soyad</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 15px; background: #f8fafc;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #475569; margin-bottom: 8px;">E-posta Adresi</label>
                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 15px; background: #f8fafc;">
            </div>

            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;">BİLGİLERİ KAYDET</button>
            
            @if (session('status') === 'profile-updated')
                <span style="color: #10b981; font-weight: bold; margin-left: 15px;">✅ Kaydedildi!</span>
            @endif
        </form>
    </div>

    <div style="background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04); margin-bottom: 30px; border: 1px solid #f1f5f9;">
        <h3 style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px;">🔒 Şifreyi Güncelle</h3>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">Hesabınızın güvende kalması için uzun ve rastgele bir şifre kullandığınızdan emin olun.</p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #475569; margin-bottom: 8px;">Mevcut Şifre</label>
                <input type="password" name="current_password" required style="width: 100%; max-width: 500px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 15px; background: #f8fafc;">
                @if($errors->updatePassword->has('current_password'))
                    <span style="color: #e53e3e; font-size: 13px; font-weight: bold; display: block; margin-top: 5px;">• {{ $errors->updatePassword->first('current_password') }}</span>
                @endif
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #475569; margin-bottom: 8px;">Yeni Şifre</label>
                <input type="password" name="password" required style="width: 100%; max-width: 500px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 15px; background: #f8fafc;">
                @if($errors->updatePassword->has('password'))
                    <span style="color: #e53e3e; font-size: 13px; font-weight: bold; display: block; margin-top: 5px;">• {{ $errors->updatePassword->first('password') }}</span>
                @endif
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #475569; margin-bottom: 8px;">Yeni Şifre (Tekrar)</label>
                <input type="password" name="password_confirmation" required style="width: 100%; max-width: 500px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 15px; background: #f8fafc;">
            </div>

            <button type="submit" style="background: #10b981; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;">ŞİFREYİ DEĞİŞTİR</button>

            @if (session('status') === 'password-updated')
                <span style="color: #10b981; font-weight: bold; margin-left: 15px;">✅ Şifreniz Güncellendi!</span>
            @endif
        </form>
    </div>

    <div style="background: #fff5f5; border: 1px solid #fed7d7; padding: 35px; border-radius: 15px; margin-bottom: 30px;">
        <h3 style="margin-top: 0; color: #c53030; border-bottom: 2px solid #fed7d7; padding-bottom: 10px; margin-bottom: 15px;">⚠️ Hesabı Sil</h3>
        <p style="color: #742a2a; font-size: 14px; margin-bottom: 20px;">Hesabınız silindiğinde, tüm özgeçmişleriniz ve verileriniz kalıcı olarak silinecektir. Bu işlem geri alınamaz!</p>

        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Tüm verileriniz silinecek! Gerçekten hesabınızı silmek istiyor musunuz?');">
            @csrf
            @method('delete')
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; font-size: 13px; color: #c53030; margin-bottom: 8px;">Güvenlik İçin Şifrenizi Girin</label>
                <input type="password" name="password" required placeholder="Şifrenizi doğrulayın" style="width: 100%; max-width: 500px; padding: 12px; border: 1px solid #feb2b2; border-radius: 8px; outline: none; font-size: 15px;">
                @if($errors->userDeletion->has('password'))
                    <span style="color: #e53e3e; font-size: 13px; font-weight: bold; display: block; margin-top: 5px;">• {{ $errors->userDeletion->first('password') }}</span>
                @endif
            </div>

            <button type="submit" style="background: #e53e3e; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;">HESABIMI KALICI OLARAK SİL</button>
        </form>
    </div>

</div>
@endsection