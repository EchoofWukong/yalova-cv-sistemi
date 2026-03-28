<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Süper Admin Paneli</title>
    <link rel="icon" type="image/png" href="{{ asset('yalova-logo.png') }}">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b; zoom:0.9;}
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #1e293b; padding: 20px 30px; border-radius: 15px; color: white; }
        .header a { color: #cbd5e1; text-decoration: none; font-weight: bold; background: #334155; padding: 10px 20px; border-radius: 8px; transition: 0.3s; }
        .header a:hover { background: #475569; color: white; }
        
        /* İstatistik Kartları */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #3b82f6; }
        .stat-card h3 { margin: 0; color: #64748b; font-size: 16px; text-transform: uppercase; }
        .stat-card .number { font-size: 40px; font-weight: bold; color: #1e293b; margin-top: 10px; }

        /* Tablolar */
        .card { background: white; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 30px; }
        .card h2 { margin-top: 0; color: #0f172a; font-size: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 13px; }
        tr:hover { background: #f8fafc; }
        
        /* Butonlar */
        .btn { padding: 8px 15px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; color: white; font-size: 13px; transition: 0.2s; }
        .btn-approve { background: #10b981; }
        .btn-approve:hover { background: #059669; }
        .btn-delete { background: #ef4444; }
        .btn-delete:hover { background: #dc2626; }
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .bg-blue { background: #dbeafe; color: #1e40af; }
        .bg-yellow { background: #fef08a; color: #854d0e; }
        .bg-green { background: #d1fae5; color: #065f46; }
        
        .alert { padding: 15px; background: #d1fae5; color: #065f46; border-radius: 10px; margin-bottom: 20px; font-weight: bold; border-left: 5px solid #10b981; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div>
            <h1 style="margin: 0; font-size: 24px;">👑 Süper Admin Paneli</h1>
            <p style="margin: 5px 0 0 0; color: #94a3b8;">Yalova CV Sistemi Yönetim Merkezi</p>
        </div>
        <a href="{{ route('dashboard') }}">⬅ Ana Sisteme Dön</a>
    </div>

    @if(session('success'))
        <div class="alert">✅ {{ session('success') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card" style="border-color: #3b82f6;">
            <h3>🎓 Toplam Öğrenci</h3>
            <div class="number">{{ $istatistikler['ogrenci_sayisi'] }}</div>
        </div>
        <div class="stat-card" style="border-color: #8b5cf6;">
            <h3>👨‍🏫 Toplam Akademisyen</h3>
            <div class="number">{{ $istatistikler['hoca_sayisi'] }}</div>
        </div>
        <div class="stat-card" style="border-color: #f59e0b;">
            <h3>⏳ Onay Bekleyen</h3>
            <div class="number" style="color: #d97706;">{{ $istatistikler['onay_bekleyen'] }}</div>
        </div>
    </div>

    <div class="card">
        <h2>🚨 Onay Bekleyen Akademisyenler</h2>
        @if($bekleyenHocalar->count() > 0)
            <table>
                <tr>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Bölüm</th>
                    <th>Kayıt Tarihi</th>
                    <th>İşlem</th>
                </tr>
                @foreach($bekleyenHocalar as $hoca)
                <tr>
                    <td><strong>{{ $hoca->name }}</strong></td>
                    <td>{{ $hoca->email }}</td>
                    <td><span class="badge bg-yellow">{{ $hoca->department }}</span></td>
                    <td>{{ $hoca->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.approve', $hoca->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-approve">✅ ONAYLA</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        @else
            <p style="color: #64748b; font-style: italic;">Şu an onay bekleyen akademisyen bulunmuyor.</p>
        @endif
    </div>

    <div class="card">
        <h2>👥 Sistemdeki Tüm Kullanıcılar</h2>
        <table>
            <tr>
                <th>Rol</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Bölüm</th>
                <th>Durum</th>
                <th>İşlem (Tehlikeli)</th>
            </tr>
            @foreach($tumKullanicilar as $user)
            <tr>
                <td>
                    @if($user->role == 'student') <span class="badge bg-blue">🎓 Öğrenci</span>
                    @else <span class="badge bg-green">👨‍🏫 Akademisyen</span> @endif
                </td>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->department }}</td>
                <td>
                    @if($user->is_approved) <span style="color: green; font-weight: bold;">✔ Aktif</span>
                    @else <span style="color: orange; font-weight: bold;">⏳ Beklemede</span> @endif
                </td>
                <td>
                    <form action="{{ route('admin.delete', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ DİKKAT: Bu kullanıcıyı ve ona ait tüm CV\'leri silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">🗑️ SİL</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

</body>
</html>