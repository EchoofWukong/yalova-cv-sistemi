@extends('layouts.app')

@section('content')
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .print-alan { padding: 40px 0; }
    
    .top-bar { max-width: 900px; margin: 0 auto 20px auto; display: flex; justify-content: space-between; }
    .btn { text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer; border: none; font-size: 14px; }
    .btn-back { background: #4A5568; } .btn-back:hover { background: #2d3748; }
    .btn-print { background: #38a169; } .btn-print:hover { background: #2f855a; }
    
    .cv-wrapper { max-width: 900px; margin: 0 auto; background: white; min-height: 1050px; box-shadow: 0 0 20px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
    
    @media print {
        body { background: white; padding: 0; }
        header, footer, .top-bar, .header-nav, .site-footer { display: none !important; }
        .print-alan { padding: 0; }
        .cv-wrapper { box-shadow: none; width: 100%; min-height: auto; margin: 0; }
    }

    .profil-cerceve { display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; margin: 0 auto; }
    .profil-cerceve img { width: 100%; height: 100%; object-fit: cover; }

    .sablon-kurumsal .sol { width: 35%; background-color: var(--ana-renk); color: var(--yazi-rengi); padding: 40px; }
    .sablon-kurumsal .sag { width: 65%; padding: 50px; color: #2d3748; }
    .sablon-kurumsal .profil-cerceve { width: 160px; height: 160px; border-radius: 50%; background: white; color: var(--ana-renk); font-size: 60px; border: 4px solid var(--yan-renk); margin-bottom: 30px; }
    .sablon-kurumsal .baslik-sol { font-size: 15px; border-bottom: 1px solid var(--yan-renk); padding-bottom: 5px; margin-bottom: 15px; text-transform: uppercase; font-weight: bold; margin-top: 30px; }
    .sablon-kurumsal .bilgi-satir { margin-bottom: 15px; font-size: 14px; line-height: 1.5; }
    .sablon-kurumsal .bilgi-satir strong { display: block; font-size: 11px; text-transform: uppercase; margin-bottom: 2px; opacity: 0.8; }
    .sablon-kurumsal .isim { font-size: 40px; text-transform: uppercase; border-bottom: 3px solid var(--yan-renk); padding-bottom: 15px; margin-bottom: 25px; font-weight: 900; color: #1a202c;}
    .sablon-kurumsal .hakkimda-yazi { font-size: 15px; line-height: 1.6; margin-bottom: 30px; text-align: justify; }
    .sablon-kurumsal .ana-baslik { font-size: 20px; color: var(--ana-renk); margin: 30px 0 15px 0; font-weight: bold; text-transform: uppercase; border-left: 5px solid var(--yan-renk); padding-left: 10px; }
    .sablon-kurumsal .item-kutu { margin-bottom: 20px; }
    .sablon-kurumsal .item-baslik { font-size: 16px; font-weight: bold; }
    .sablon-kurumsal .item-tarih { font-size: 13px; color: #718096; float: right; font-weight: bold; }
    .sablon-kurumsal .item-alt { font-size: 14px; font-style: italic; margin-bottom: 5px; }
    .sablon-kurumsal .item-aciklama { font-size: 14px; line-height: 1.5; }

    .sablon-minimalist { flex-direction: column; padding: 50px 60px; color: #333; }
    .sablon-minimalist .sol, .sablon-minimalist .sag { width: 100%; padding: 0; background: white; }
    .sablon-minimalist .profil-cerceve, .sablon-minimalist .baslik-sol { display: none; }
    .sablon-minimalist .isim { font-size: 38px; font-weight: bold; margin-bottom: 8px; color: #111; }
    .sablon-minimalist .iletisim-yatay { display: flex; flex-wrap: wrap; gap: 15px; font-size: 13px; border-bottom: 1px solid #e2e8f0; padding-bottom: 25px; margin-bottom: 30px; }
    .sablon-minimalist .ana-baslik { font-size: 18px; font-weight: bold; margin: 35px 0 15px 0; color: #111; }
    .sablon-minimalist .hakkimda-yazi { border-left: 3px solid #2b6cb0; padding-left: 15px; font-size: 14px; margin-bottom: 30px; line-height: 1.6; }
    .sablon-minimalist .item-kutu { border-left: 3px solid #2b6cb0; padding-left: 15px; margin-bottom: 20px; }
    .sablon-minimalist .item-baslik-satir { display: flex; justify-content: space-between; font-weight: bold; font-size: 15px; margin-bottom: 4px; }
    .sablon-minimalist .item-alt { font-size: 13px; margin-bottom: 6px; }
    .sablon-minimalist .item-tarih { font-size: 13px; color: #718096; }
    .sablon-minimalist .item-aciklama { font-size: 13.5px; line-height: 1.5; }
    .sablon-minimalist .beceriler-liste { border-left: 3px solid #2b6cb0; padding-left: 15px; font-size: 14px; line-height: 1.8; }

    .sablon-modern .sol { width: 35%; background: #2d3748; color: white; padding: 40px; border-top: 15px solid #ecc94b; }
    .sablon-modern .sag { width: 65%; padding: 50px; color: #1a202c; }
    .sablon-modern .profil-cerceve { width: 140px; height: 140px; border-radius: 50%; background: white; color: #2d3748; font-size: 50px; margin-bottom: 30px; border: 4px solid #ecc94b; }
    .sablon-modern .baslik-sol { font-size: 16px; border-bottom: 2px solid #4a5568; padding-bottom: 5px; margin-bottom: 15px; text-transform: uppercase; margin-top: 30px; color: #ecc94b; }
    .sablon-modern .bilgi-satir { margin-bottom: 15px; font-size: 14px; }
    .sablon-modern .bilgi-satir strong { display: block; color: #a0aec0; font-size: 12px; text-transform: uppercase; margin-bottom: 2px; }
    .sablon-modern .isim { font-size: 38px; text-transform: uppercase; border-bottom: 4px solid #ecc94b; padding-bottom: 10px; margin-bottom: 25px; font-weight: 900;}
    .sablon-modern .hakkimda-yazi { font-size: 15px; line-height: 1.6; margin-bottom: 30px; text-align: justify; }
    .sablon-modern .ana-baslik { font-size: 20px; color: #2d3748; margin: 30px 0 15px 0; font-weight: bold; border-left: 5px solid #ecc94b; padding-left: 10px; }
    .sablon-modern .item-kutu { padding-left: 15px; margin-left: 5px; margin-bottom: 25px; border-left: 2px dashed #cbd5e0; }
    .sablon-modern .item-tarih { font-size: 14px; color: #d69e2e; font-weight: bold; margin-bottom: 3px; }
    .sablon-modern .item-baslik { font-size: 18px; font-weight: bold; }
    .sablon-modern .item-alt { font-size: 15px; font-style: italic; margin-bottom: 8px; }
    .sablon-modern .item-aciklama { font-size: 14px; line-height: 1.5; }

    .sablon-klasik { flex-direction: column; padding: 50px 60px; font-family: 'Times New Roman', Times, serif; }
    .sablon-klasik .sol, .sablon-klasik .sag { width: 100%; padding: 0; background: white; color: black; }
    .sablon-klasik .profil-cerceve { width: 100px; height: 120px; border-radius: 0; background: white; color: black; font-size: 40px; margin-bottom: 20px; border: 1px solid #ccc;}
    .sablon-klasik .baslik-sol { display: none; }
    .sablon-klasik .isim { font-size: 40px; text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 15px; font-weight: bold; text-transform: uppercase; }
    .sablon-klasik .iletisim-yatay { display: flex; justify-content: center; gap: 20px; font-size: 14px; margin-bottom: 30px; flex-wrap: wrap; }
    .sablon-klasik .hakkimda-yazi { font-size: 15px; line-height: 1.5; margin-bottom: 30px; text-align: justify; }
    .sablon-klasik .ana-baslik { font-size: 20px; border-bottom: 1px solid black; padding-bottom: 5px; margin: 30px 0 15px 0; text-transform: uppercase; font-weight: bold; }
    .sablon-klasik .item-kutu { margin-bottom: 20px; }
    .sablon-klasik .item-baslik-satir { display: flex; justify-content: space-between; font-weight: bold; font-size: 16px; margin-bottom: 5px; }
    .sablon-klasik .item-tarih { font-size: 14px; }
    .sablon-klasik .item-alt { font-style: italic; font-size: 15px; margin-bottom: 5px; }
    .sablon-klasik .item-aciklama { font-size: 14px; line-height: 1.5; }
    .sablon-klasik .beceriler-liste { font-size: 15px; line-height: 1.8; }

    .sablon-yaratici { flex-direction: column; background: #f8fafc; }
    .sablon-yaratici .sol { width: 100%; background: linear-gradient(135deg, #e53e3e, #9b2c2c); color: white; padding: 50px 30px; text-align: center; }
    .sablon-yaratici .sag { width: 100%; padding: 40px 50px; }
    .sablon-yaratici .profil-cerceve { width: 120px; height: 120px; border-radius: 20px; background: white; color: #e53e3e; font-size: 50px; margin-bottom: 20px; transform: rotate(5deg); box-shadow: 0 10px 15px rgba(0,0,0,0.2); }
    .sablon-yaratici .baslik-sol { display: none; }
    .sablon-yaratici .isim { font-size: 45px; font-weight: 900; margin-bottom: 15px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
    .sablon-yaratici .iletisim-yatay { display: flex; justify-content: center; gap: 20px; font-size: 15px; flex-wrap: wrap; }
    .sablon-yaratici .hakkimda-yazi { font-size: 16px; line-height: 1.7; margin-bottom: 30px; text-align: center; font-style: italic; }
    .sablon-yaratici .ana-baslik { font-size: 22px; color: #e53e3e; font-weight: 900; margin: 20px 0; display: inline-block; border-bottom: 4px solid #fed7d7; padding-bottom: 5px; }
    .sablon-yaratici .item-kutu { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 5px solid #e53e3e; }
    .sablon-yaratici .item-tarih { display: inline-block; background: #fed7d7; color: #c53030; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 10px; }
    .sablon-yaratici .item-baslik { font-size: 18px; font-weight: bold; }
    .sablon-yaratici .item-alt { font-size: 15px; margin-bottom: 10px; }
    .sablon-yaratici .beceriler-liste span { display: inline-block; background: #e2e8f0; color: #4a5568; padding: 5px 12px; border-radius: 8px; margin: 5px; font-size: 13px; font-weight: bold; }
</style>

<div class="print-alan">
    <div class="top-bar">
        @if(Auth::user()->role === 'teacher')
            <a href="{{ route('cv.search') }}" class="btn btn-back">⬅ Arama Sayfasına Dön</a>
        @else
            <a href="{{ url()->previous() }}" class="btn btn-back">⬅ Panele Dön</a>
        @endif
        <button onclick="window.print()" class="btn btn-print">🖨️ PDF Olarak İndir / Yazdır</button>
    </div>

    <div class="cv-wrapper sablon-{{ $cv->sablon ?? 'modern' }}" style="--ana-renk: {{ $cv->ana_renk ?? '#1e3a8a' }}; --yan-renk: {{ $cv->yan_renk ?? '#dbeafe' }}; --yazi-rengi: {{ $cv->yazi_rengi ?? '#ffffff' }};">
        
        <div class="sol">
            <div class="profil-cerceve">
                @if($cv->profil_fotografi)
                    <img src="{{ asset($cv->profil_fotografi) }}" alt="Profil Fotoğrafı">
                @else
                    {{ mb_substr($cv->ad_soyad ?? 'A', 0, 1) }}
                @endif
            </div>
            
            @if(in_array($cv->sablon ?? 'modern', ['klasik', 'yaratici', 'minimalist']))
                <h1 class="isim">{{ $cv->ad_soyad }}</h1>
                <div class="iletisim-yatay">
                    <span>📧 {{ $cv->eposta }}</span>
                    @if($cv->telefon)<span>📱 {{ $cv->telefon }}</span>@endif
                    @if($cv->adres)<span>📍 {{ $cv->adres }}</span>@endif
                    @if($cv->linkedin)<span>🔗 {{ $cv->linkedin }}</span>@endif
                </div>
            @else
                <h3 class="baslik-sol">İletişim</h3>
                <div class="bilgi-satir"><strong>E-posta</strong>{{ $cv->eposta }}</div>
                @if($cv->telefon)<div class="bilgi-satir"><strong>Telefon</strong>{{ $cv->telefon }}</div>@endif
                @if($cv->adres)<div class="bilgi-satir"><strong>Adres</strong>{{ $cv->adres }}</div>@endif
                @if($cv->linkedin)<div class="bilgi-satir"><strong>LinkedIn</strong>{{ $cv->linkedin }}</div>@endif

                @if($cv->yetenekler)
                    <h3 class="baslik-sol">Beceriler</h3>
                    <div class="bilgi-satir" style="line-height: 1.8;">{{ $cv->yetenekler }}</div>
                @endif
            @endif
        </div>

        <div class="sag">
            @if(in_array($cv->sablon ?? 'modern', ['modern', 'kurumsal']))
                <h1 class="isim">{{ $cv->ad_soyad }}</h1>
            @endif

            @if($cv->hakkimda)
                @if(($cv->sablon ?? 'modern') == 'minimalist') <h2 class="ana-baslik">Profil</h2> @endif
                <p class="hakkimda-yazi">{{ $cv->hakkimda }}</p>
            @endif

            @if($cv->deneyimler && $cv->deneyimler->count() > 0)
                <h2 class="ana-baslik">@if(($cv->sablon ?? 'modern') != 'minimalist')💼@endif İş Tecrübesi</h2>
                @foreach($cv->deneyimler as $deneyim)
                    <div class="item-kutu">
                        @if(in_array($cv->sablon ?? 'modern', ['klasik', 'minimalist']))
                            <div class="item-baslik-satir">
                                <div>{{ $deneyim->sirket_adi }}</div>
                                <div class="item-tarih">{{ $deneyim->baslangic_tarihi }} - {{ $deneyim->bitis_tarihi }}</div>
                            </div>
                            <div class="item-alt">{{ $deneyim->pozisyon }}</div>
                        @else
                            <div class="item-tarih">{{ $deneyim->baslangic_tarihi }} - {{ $deneyim->bitis_tarihi }}</div>
                            <div class="item-baslik">{{ $deneyim->sirket_adi }}</div>
                            <div class="item-alt">{{ $deneyim->pozisyon }}</div>
                        @endif
                        <div class="item-aciklama">{{ $deneyim->aciklama }}</div>
                    </div>
                @endforeach
            @endif

            @if($cv->egitimler && $cv->egitimler->count() > 0)
                <h2 class="ana-baslik">@if(($cv->sablon ?? 'modern') != 'minimalist')🎓@endif Eğitim Bilgileri</h2>
                @foreach($cv->egitimler as $egitim)
                    <div class="item-kutu">
                        @if(in_array($cv->sablon ?? 'modern', ['klasik', 'minimalist']))
                            <div class="item-baslik-satir">
                                <div>{{ $egitim->okul_adi }}</div>
                                <div class="item-tarih">{{ $egitim->baslangic_tarihi }} - {{ $egitim->bitis_tarihi }}</div>
                            </div>
                            <div class="item-alt">{{ $egitim->bolum }}</div>
                        @else
                            <div class="item-tarih">{{ $egitim->baslangic_tarihi }} - {{ $egitim->bitis_tarihi }}</div>
                            <div class="item-baslik">{{ $egitim->okul_adi }}</div>
                            <div class="item-alt">{{ $egitim->bolum }}</div>
                        @endif
                    </div>
                @endforeach
            @endif

            @if(in_array($cv->sablon ?? 'modern', ['klasik', 'yaratici', 'minimalist']) && $cv->yetenekler)
                <h2 class="ana-baslik">@if(($cv->sablon ?? 'modern') != 'minimalist')🛠️@endif Core Competencies / Beceriler</h2>
                <div class="beceriler-liste">
                    @if(($cv->sablon ?? 'modern') == 'yaratici')
                        @foreach(explode(',', $cv->yetenekler) as $yetenek)
                            <span>{{ trim($yetenek) }}</span>
                        @endforeach
                    @else
                        {{ $cv->yetenekler }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection