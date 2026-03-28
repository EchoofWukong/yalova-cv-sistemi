<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CertificateController extends Controller
{
    // Sertifika Yükleme Motoru
    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'dosya' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // Maksimum 5MB
        ]);

        if ($request->hasFile('dosya')) {
            $file = $request->file('dosya');
            $filename = time() . '_sertifika_' . preg_replace('/[^A-Za-z0-9.]/', '_', $file->getClientOriginalName());
            
            $path = public_path('uploads/sertifikalar');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $file->move($path, $filename);
            $dosya_yolu = 'uploads/sertifikalar/' . $filename;

            Certificate::create([
                'user_id' => Auth::id(),
                'baslik' => $request->baslik,
                'dosya_yolu' => $dosya_yolu,
            ]);

            return back()->with('success', 'Sertifikanız başarıyla yüklendi!');
        }

        return back()->withErrors(['dosya' => 'Dosya yüklenirken bir hata oluştu.']);
    }

    // Sertifika Silme Motoru
    public function destroy($id)
    {
        $sertifika = Certificate::findOrFail($id);

        // Sadece sahibi silebilir
        if ($sertifika->user_id !== Auth::id()) {
            abort(403);
        }

        // Sunucudan dosyayı fiziksel olarak tamamen sil
        if(File::exists(public_path($sertifika->dosya_yolu))) {
            File::delete(public_path($sertifika->dosya_yolu));
        }

        $sertifika->delete();
        return back()->with('success', 'Sertifikanız kalıcı olarak silindi.');
    }
}