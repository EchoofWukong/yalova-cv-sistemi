<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Dosya işlemleri için eklendi

class CvController extends Controller
{
    public function create()
    {
        return view('cv.create');
    }

    public function store(Request $request)
    {
        $foto_yolu = null;
        if ($request->hasFile('profil_fotografi')) {
            $file = $request->file('profil_fotografi');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            
            // Klasör yolunu belirle
            $path = public_path('uploads');

            // KRİTİK: Klasör yoksa oluştur ve tam yetki (0777) ver
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // Dosyayı taşı
            $file->move($path, $filename);
            $foto_yolu = 'uploads/' . $filename;
        }

        $cv = Cv::create([
            'user_id' => Auth::id(),
            'sablon' => $request->sablon ?? 'modern',
            'ana_renk' => $request->ana_renk ?? '#1e3a8a',
            'yan_renk' => $request->yan_renk ?? '#dbeafe',
            'yazi_rengi' => $request->yazi_rengi ?? '#ffffff',
            'ad_soyad' => $request->ad_soyad,
            'eposta' => $request->eposta,
            'telefon' => $request->telefon,
            'linkedin' => $request->linkedin,
            'adres' => $request->adres,
            'hakkimda' => $request->hakkimda,
            'yetenekler' => $request->yetenekler,
            'profil_fotografi' => $foto_yolu,
        ]);

        if ($request->has('egitim_okul')) {
            foreach ($request->egitim_okul as $key => $okul) {
                if ($okul != null) {
                    $cv->egitimler()->create([
                        'okul_adi' => $okul,
                        'bolum' => $request->egitim_bolum[$key] ?? '',
                        'baslangic_tarihi' => $request->egitim_bas[$key] ?? '',
                        'bitis_tarihi' => $request->egitim_bitis[$key] ?? '',
                    ]);
                }
            }
        }

        if ($request->has('deneyim_sirket')) {
            foreach ($request->deneyim_sirket as $key => $sirket) {
                if ($sirket != null) {
                    $cv->deneyimler()->create([
                        'sirket_adi' => $sirket,
                        'pozisyon' => $request->deneyim_pozisyon[$key] ?? '',
                        'baslangic_tarihi' => $request->deneyim_bas[$key] ?? '',
                        'bitis_tarihi' => $request->deneyim_bitis[$key] ?? '',
                        'aciklama' => $request->deneyim_aciklama[$key] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'CV Başarıyla Oluşturuldu!');
    }

    public function show($id)
    {
        $cv = Cv::with(['egitimler', 'deneyimler'])->findOrFail($id);

        if (Auth::id() !== $cv->user_id && Auth::user()->role !== 'teacher') {
            abort(403, 'Bu özgeçmişi görüntüleme yetkiniz yok.');
        }

        return view('cv.detay', compact('cv'));
    }

    public function edit($id)
    {
        $cv = Cv::with(['egitimler', 'deneyimler'])->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('cv.edit', compact('cv'));
    }

    public function update(Request $request, $id)
    {
        $cv = Cv::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $foto_yolu = $cv->profil_fotografi;
        if ($request->hasFile('profil_fotografi')) {
            // Eski fotoğrafı sil
            if($foto_yolu && File::exists(public_path($foto_yolu))) {
                File::delete(public_path($foto_yolu));
            }

            $file = $request->file('profil_fotografi');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            
            // Klasör yolunu belirle ve Render için kontrol et
            $path = public_path('uploads');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $file->move($path, $filename);
            $foto_yolu = 'uploads/' . $filename;
        }

        $cv->update([
            'sablon' => $request->sablon ?? $cv->sablon,
            'ana_renk' => $request->ana_renk ?? $cv->ana_renk,
            'yan_renk' => $request->yan_renk ?? $cv->yan_renk,
            'yazi_rengi' => $request->yazi_rengi ?? $cv->yazi_rengi,
            'ad_soyad' => $request->ad_soyad,
            'eposta' => $request->eposta,
            'telefon' => $request->telefon,
            'linkedin' => $request->linkedin,
            'adres' => $request->adres,
            'hakkimda' => $request->hakkimda,
            'yetenekler' => $request->yetenekler,
            'profil_fotografi' => $foto_yolu,
        ]);

        $cv->egitimler()->delete();
        if ($request->has('egitim_okul')) {
            foreach ($request->egitim_okul as $key => $okul) {
                if ($okul != null) {
                    $cv->egitimler()->create([
                        'okul_adi' => $okul,
                        'bolum' => $request->egitim_bolum[$key] ?? '',
                        'baslangic_tarihi' => $request->egitim_bas[$key] ?? '',
                        'bitis_tarihi' => $request->egitim_bitis[$key] ?? '',
                    ]);
                }
            }
        }

        $cv->deneyimler()->delete();
        if ($request->has('deneyim_sirket')) {
            foreach ($request->deneyim_sirket as $key => $sirket) {
                if ($sirket != null) {
                    $cv->deneyimler()->create([
                        'sirket_adi' => $sirket,
                        'pozisyon' => $request->deneyim_pozisyon[$key] ?? '',
                        'baslangic_tarihi' => $request->deneyim_bas[$key] ?? '',
                        'bitis_tarihi' => $request->deneyim_bitis[$key] ?? '',
                        'aciklama' => $request->deneyim_aciklama[$key] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'CV Başarıyla Güncellendi!');
    }

    public function destroy($id)
    {
        $cv = Cv::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if($cv->profil_fotografi && File::exists(public_path($cv->profil_fotografi))) {
            File::delete(public_path($cv->profil_fotografi));
        }
        $cv->delete();
        return redirect()->route('dashboard')->with('success', 'CV başarıyla silindi!');
    }
    
   public function search(Request $request)
{
    $loggedInUser = \Illuminate\Support\Facades\Auth::user();

    // 1. AJAX/FETCH İLE (ARAMA KUTUSUNDAN) GELEN İSTEK
    if ($request->ajax() || $request->wantsJson()) {
        $query = $request->input('query');
        
        $cvs = \App\Models\Cv::with('user')
            ->whereHas('user', function($q) use ($query, $loggedInUser) {
                // Sadece onaylı öğrenciler
                $q->where('role', 'student')
                  ->where('is_approved', true);

                // BÖLÜM KİLİDİ: Yönetim değilse, sadece kendi bölümündekileri gör
                if ($loggedInUser && $loggedInUser->department !== 'Çınarcık MYO (Yönetim)') {
                    $q->where('department', $loggedInUser->department);
                }

                // EĞER ARAMA KUTUSUNA YAZI YAZILDIYSA FİLTRELE
                if ($query) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower(trim($query), 'UTF-8') . '%']);
                }
            })
            ->get();

        $results = $cvs->map(function($cv) {
            return [
                'id' => $cv->id,
                'ad_soyad' => $cv->user->name ?? 'İsimsiz', 
                'sablon' => $cv->sablon ?? 'modern', 
                'updated_at' => $cv->updated_at ? $cv->updated_at->toIso8601String() : null,
            ];
        });

        return response()->json($results);
    }

    // 2. SAYFAYA DİREKT (NORMAL) GİRİŞ
    // Bu kısmı değiştirdik: Sayfa açılırken de dataları View'a gönderiyoruz (JS onu çekecek)
    return view('cv.search'); // (veya sadece 'search', dosya adına göre)
}
}