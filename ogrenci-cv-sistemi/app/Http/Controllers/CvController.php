<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use Illuminate\Support\Facades\Auth;

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
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
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

        // YENİ TEMİZ DOSYAMIZA (detay.blade.php) YÖNLENDİRİYORUZ
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
            if($foto_yolu && file_exists(public_path($foto_yolu))) {
                unlink(public_path($foto_yolu));
            }
            $file = $request->file('profil_fotografi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
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
        if($cv->profil_fotografi && file_exists(public_path($cv->profil_fotografi))) {
            unlink(public_path($cv->profil_fotografi));
        }
        $cv->delete();
        return redirect()->route('dashboard')->with('success', 'CV başarıyla silindi!');
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if ($request->ajax()) {
            $sonuclar = [];
            if ($query) {
                $lower = mb_strtolower($query, 'UTF-8');
                $upper = mb_strtoupper($query, 'UTF-8');
                $title = mb_convert_case($query, MB_CASE_TITLE, 'UTF-8');

                $sonuclar = Cv::where(function($q) use ($query, $lower, $upper, $title) {
                                $q->where('ad_soyad', 'LIKE', "%{$query}%")
                                  ->orWhere('ad_soyad', 'LIKE', "%{$lower}%")
                                  ->orWhere('ad_soyad', 'LIKE', "%{$upper}%")
                                  ->orWhere('ad_soyad', 'LIKE', "%{$title}%")
                                  ->orWhere('eposta', 'LIKE', "%{$lower}%");
                            })
                            ->where('user_id', '!=', Auth::id())
                            ->get();
            }
            return response()->json($sonuclar);
        }

        return view('cv.search', compact('query'));
    }
}