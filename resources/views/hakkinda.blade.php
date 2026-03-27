@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 0 auto; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
    
    <div style="text-align: center; margin-bottom: 40px;">
        <img src="{{ asset('yalova-logo.png') }}" alt="Yalova Logo" style="width: 100px; margin-bottom: 20px;">
        <h1 style="color: #1e3a8a; font-size: 32px; margin-bottom: 10px;">Yalova Üniversitesi CV Sistemi</h1>
        <p style="color: #64748b; font-size: 16px;">Öğrencilerimizi profesyonel iş dünyasına bir adım önde hazırlıyoruz.</p>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 15px;">🚀 Proje Hakkında</h3>
        <p style="color: #475569; line-height: 1.7; font-size: 15px;">
            Bu sistem, Yalova Üniversitesi öğrencileri için özel olarak tasarlanmış modern bir özgeçmiş oluşturma platformudur. Öğrencilerimiz, sektör standartlarına uygun profesyonel şablonlarla dakikalar içinde CV'lerini hazırlayabilir ve diledikleri zaman PDF olarak bilgisayarlarına indirebilirler.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px;">
        <div style="background: #f8fafc; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h4 style="color: #1e3a8a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">🎓 Öğrenciler İçin</h4>
            <ul style="color: #475569; font-size: 14px; line-height: 1.8; padding-left: 20px; margin: 0;">
                <li>Hazır ve şık şablon seçenekleri</li>
                <li>Kolay ve hızlı düzenleme paneli</li>
                <li>Tek tıkla PDF olarak indirebilme</li>
                <li>Sınırsız özgeçmiş oluşturma</li>
            </ul>
        </div>
        <div style="background: #f8fafc; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <h4 style="color: #1e3a8a; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">👨‍🏫 Akademisyenler İçin</h4>
            <ul style="color: #475569; font-size: 14px; line-height: 1.8; padding-left: 20px; margin: 0;">
                <li>Akıllı öğrenci arama motoru</li>
                <li>Kariyer ve gelişim takibi</li>
                <li>Detaylı CV görüntüleme paneli</li>
                <li>Sisteme güvenli erişim (Onaylı)</li>
            </ul>
        </div>
    </div>

    <div style="text-align: center; padding-top: 25px; border-top: 1px solid #f1f5f9;">
        <p style="color: #94a3b8; font-size: 14px; margin: 0;">Sistem Mimarisi ve Geliştirme: <strong>Gökhan Onmaz</strong></p>
    </div>
</div>
@endsection 