@extends('layouts.app')

@section('content')
<style>
    /* ANİMASYONLAR */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
    
    .hover-lift { transition: all 0.3s ease; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
    
    .cv-card, .cert-card { transition: all 0.3s ease; border: 1px solid #e2e8f0; }
    .cv-card:hover, .cert-card:hover { border-color: #cbd5e1; transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }

    /* SEKMELER */
    .tab-container { display: flex; gap: 15px; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
    .tab-btn { background: none; border: none; font-size: 18px; font-weight: bold; color: #64748b; cursor: pointer; padding: 10px 20px; border-radius: 10px; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
    .tab-btn:hover { background: #f1f5f9; color: #1e293b; }
    .tab-btn.active { background: #1e3a8a; color: white; box-shadow: 0 4px 10px rgba(30,58,138,0.2); }
    
    .tab-content { display: none; animation: fadeUp 0.4s ease-out forwards; }
    .tab-content.active { display: block; }

    /* ÖĞRENCİDE YUKARIDA KALMA SORUNU ÇÖZÜMÜ */
    .dashboard-container { 
        max-width: 1200px; 
        margin: 40px auto; /* 40px üst boşluk verildi */
        padding: 0 20px;
        zoom: 0.95; 
    }
</style>

<div class="dashboard-container animate-fade-up">

    @if(session('success'))
        <div style="background: #10b981; color: white; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->role === 'admin')
        <div style="margin-bottom: 30px; text-align: center;">
            <a href="{{ route('admin.dashboard') }}" class="hover-lift" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: white; padding: 15px 35px; text-decoration: none; border-radius: 12px; font-weight: 800; font-size: 16px; display: inline-flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">👑</span> SÜPER ADMİN PANELİNE GİT
            </a>
        </div>
    @endif
    
    @if(Auth::user()->role === 'teacher' || Auth::user()->role === 'admin')
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%); padding: 40px; border-radius: 20px; color: white; margin-bottom: 40px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 15px 30px rgba(30,58,138,0.2);">
            <div style="max-width: 600px;">
                <h2 style="margin: 0 0 15px 0; font-size: 28px;">👨‍🏫 Akademisyen Kontrol Paneli</h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.9; line-height: 1.5;">Öğrencilerinizin özgeçmişlerini inceleyin ve yetenek havuzunda arama yapın.</p>
            </div>
            <a href="{{ route('cv.search') }}" class="hover-lift" style="background: white; color: #1e3a8a; padding: 18px 35px; border-radius: 12px; text-decoration: none; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                <span>🔍</span> ÖĞRENCİ CV ARA
            </a>
        </div>
    @endif

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin:0; color: #0f172a; font-size: 24px;">Kariyer Paneli</h2>
            <a href="{{ route('profile.edit') }}" class="hover-lift" style="background: #475569; color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                <span>⚙️</span> Ayarlar
            </a>
        </div>

        <div class="tab-container">
            <button class="tab-btn active" onclick="switchTab('cv-tab', this)">📄 Özgeçmişlerim</button>
            <button class="tab-btn" onclick="switchTab('cert-tab', this)">🎓 Sertifikalarım</button>
        </div>

        <div id="cv-tab" class="tab-content active">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <p style="margin: 0; color: #64748b;">Mevcut CV'lerinizi yönetin.</p>
                <a href="{{ route('cv.create') }}" class="hover-lift" style="background: #10b981; color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold;">
                    + Yeni CV Oluştur
                </a>
            </div>

            @php $cvs = \App\Models\Cv::where('user_id', Auth::id())->get(); @endphp

            @if($cvs->count() > 0)
                <div style="display: grid; gap: 20px;">
                    @foreach($cvs as $cv)
                        <div class="cv-card" style="padding: 25px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="background: #e0e7ff; width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">💼</div>
                                <div>
                                    <strong style="font-size: 18px; color: #1e293b;">{{ $cv->ad_soyad }}</strong>
                                    <div style="margin-top:5px;">
                                        <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;">{{ $cv->sablon }} ŞABLON</span>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('cv.show', $cv->id) }}" class="hover-lift" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold;">Görüntüle</a>
                                <form action="{{ route('cv.delete', $cv->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="hover-lift" style="background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;" onclick="return confirm('Silinsin mi?')">Sil</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; background: #f8fafc; border-radius: 15px; border: 2px dashed #e2e8f0;">
                    <p>Henüz CV oluşturmadınız.</p>
                </div>
            @endif
        </div>

        <div id="cert-tab" class="tab-content">
            <div style="background: #f8fafc; padding: 25px; border-radius: 15px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                <h3 style="margin-top: 0; color: #1e293b; font-size: 18px; margin-bottom: 15px;">📥 Yeni Sertifika Yükle</h3>
                <form action="{{ route('certificate.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    @csrf
                    <div style="flex: 2; min-width: 200px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 8px;">Belge Adı</label>
                        <input type="text" name="baslik" required placeholder="Örn: İngilizce Sertifikası" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <div style="flex: 2; min-width: 200px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 8px;">Dosya Seç</label>
                        <input type="file" name="dosya" required style="width: 100%; padding: 9px; border: 1px solid #cbd5e1; border-radius: 8px; background: white;">
                    </div>
                    <button type="submit" class="hover-lift" style="background: #1e3a8a; color: white; border: none; padding: 13px 25px; border-radius: 8px; font-weight: bold; cursor: pointer;">YÜKLE</button>
                </form>
            </div>

            @php $certs = Auth::user()->certificates; @endphp
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                @if($certs && $certs->count() > 0)
                    @foreach($certs as $cert)
                        <div class="cert-card" style="padding: 20px; border-radius: 15px; background: #fafafa; text-align: center; position: relative;">
                            <form action="{{ route('certificate.destroy', $cert->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold;" onclick="return confirm('Silinsin mi?')">✖</button>
                            </form>
                            <div style="font-size: 40px; margin-bottom: 10px;">📜</div>
                            <h4 style="margin: 0; font-size: 15px;">{{ $cert->baslik }}</h4>
                            <a href="{{ asset($cert->dosya_yolu) }}" target="_blank" style="display: inline-block; margin-top: 10px; color: #3b82f6; font-weight: bold; text-decoration: none; font-size: 13px;">Görüntüle</a>
                        </div>
                    @endforeach
                @else
                    <p style="grid-column: 1/-1; text-align: center; color: #94a3b8;">Henüz sertifika yüklenmedi.</p>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    function switchTab(tabId, btnElement) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        btnElement.classList.add('active');
    }
</script>
@endsection