@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    
    @if(Auth::user()->role === 'teacher')
        <div style="background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%); padding: 40px; border-radius: 20px; color: white; margin-bottom: 40px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 15px 30px rgba(30,58,138,0.2);">
            <div style="max-width: 600px;">
                <h2 style="margin: 0 0 15px 0; font-size: 28px;">👨‍🏫 Akademisyen Kontrol Paneli</h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.9; line-height: 1.5;">Öğrencilerinizin oluşturduğu özgeçmişleri inceleyin, yetenek havuzunda arama yapın ve kariyer gelişimlerini takip edin.</p>
            </div>
            <a href="{{ route('cv.search') }}" style="background: white; color: #1e3a8a; padding: 18px 35px; border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 15px; transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 10px;">
                <span>🔍</span> ÖĞRENCİ CV ARA
            </a>
        </div>
    @endif

    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px;">
            <div>
                <h2 style="margin:0; color: #0f172a; font-size: 24px;">📄 Özgeçmişlerim</h2>
                <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">Mevcut CV'lerinizi yönetin veya yeni bir tane ekleyin.</p>
            </div>
            <a href="{{ route('cv.create') }}" style="background: #10b981; color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 10px rgba(16,185,129,0.2);">
                + Yeni CV Oluştur
            </a>
        </div>

        @php $cvs = \App\Models\Cv::where('user_id', Auth::id())->get(); @endphp

        @if($cvs->count() > 0)
            <div style="display: grid; gap: 20px;">
                @foreach($cvs as $cv)
                    <div style="border: 1px solid #e2e8f0; padding: 25px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; background: #fafafa; transition: 0.3s;">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="background: #e0e7ff; width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                💼
                            </div>
                            <div>
                                <strong style="font-size: 18px; color: #1e293b; display: block; margin-bottom: 5px;">{{ $cv->ad_soyad }}</strong>
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800; text-transform: uppercase; margin-right: 10px;">{{ $cv->sablon }} ŞABLON</span>
                                <span style="color: #64748b; font-size: 13px;">Son Güncelleme: {{ $cv->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('cv.show', $cv->id) }}" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s;">Görüntüle</a>
                            <a href="{{ route('cv.edit', $cv->id) }}" style="background: #f59e0b; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.2s;">Düzenle</a>
                            <form action="{{ route('cv.delete', $cv->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; transition: 0.2s;" onclick="return confirm('Bu CV kalıcı olarak silinecek. Emin misiniz?')">Sil</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 15px; border: 2px dashed #e2e8f0;">
                <div style="font-size: 50px; margin-bottom: 15px;">📄</div>
                <h3 style="color: #1e293b; margin-bottom: 10px;">Henüz bir CV'niz yok</h3>
                <p style="color: #64748b; margin-bottom: 20px;">İş başvurularında öne çıkmak için ilk profesyonel özgeçmişinizi hemen hazırlayın.</p>
                <a href="{{ route('cv.create') }}" style="background: #1e3a8a; color: white; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; display: inline-block;">Şimdi Oluştur →</a>
            </div>
        @endif
    </div>
</div>
@endsection