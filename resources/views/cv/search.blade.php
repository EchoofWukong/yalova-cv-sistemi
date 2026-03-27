<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Gelişmiş Öğrenci Ara - Yalova Üniversitesi</title>
    <link rel="icon" type="image/png" href="{{ asset('yalova-logo.png') }}">
    <style>
        body { zoom: 0.9; font-family: 'Segoe UI', sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px; color: #1e293b; }
        .container { max-width: 900px; margin: 0 auto; }
        .back-link { text-decoration: none; color: #64748b; font-weight: bold; margin-bottom: 25px; display: inline-block; }

        /* ARAMA ALANI */
        .search-card { background: white; padding: 30px; border-radius: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); margin-bottom: 30px; }
        input[type="text"] { width: 100%; padding: 18px 25px; border: 2px solid #e2e8f0; border-radius: 18px; font-size: 18px; outline: none; transition: 0.3s; box-sizing: border-box; }
        input[type="text"]:focus { border-color: #3b82f6; box-shadow: 0 0 0 5px rgba(59,130,246,0.1); }

        /* GRUP (ACCORDION) TASARIMI */
        .name-group { background: white; border-radius: 20px; margin-bottom: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; }
        
        .group-header { 
            padding: 20px 25px; background: #fff; cursor: pointer; 
            display: flex; justify-content: space-between; align-items: center;
            transition: 0.3s; border-bottom: 1px solid transparent;
        }
        .group-header:hover { background: #f8fafc; }
        
        /* AKTİF (AÇIK) GRUP STİLİ */
        .name-group.active .group-header { border-bottom-color: #f1f5f9; background: #f1f5f9; }
        .name-group.active .arrow { transform: rotate(180deg); color: #1e3a8a; }

        .student-name { font-size: 18px; font-weight: bold; color: #1e3a8a; display: flex; align-items: center; gap: 12px; }
        .cv-count { background: #e2e8f0; color: #475569; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .arrow { transition: 0.3s; font-size: 14px; color: #94a3b8; }

        /* CV LİSTESİ (İÇERİK) */
        .cv-list { 
            display: none; /* Başlangıçta gizli */
            padding: 15px 25px; 
            background: #fff; 
            border-top: 1px solid #f1f5f9;
        }
        
        /* AKTİF (AÇIK) LİSTE STİLİ */
        .name-group.active .cv-list { display: block; animation: slideDown 0.3s ease-out; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cv-item { 
            display: flex; justify-content: space-between; align-items: center; 
            padding: 15px 0; border-bottom: 1px dashed #e2e8f0;
        }
        .cv-item:last-child { border-bottom: none; }

        .cv-info strong { color: #334155; font-size: 15px; }
        .cv-info span { font-size: 12px; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 5px; margin-left: 8px; }

        .btn-view { 
            background: #2d3748; color: white !important; text-decoration: none; 
            padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: bold; transition: 0.2s;
        }
        .btn-view:hover { background: #e53e3e; }

        .empty-msg { text-align: center; color: #94a3b8; padding: 60px 0; }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('dashboard') }}" class="back-link">⬅ Geri Dön</a>
    
    <div class="search-card">
        <h1 style="margin-top:0; font-size: 24px;">🔍 Akıllı Arama</h1>
        <input type="text" id="live-search" placeholder="Örn: gökhan, buse, görkem..." autocomplete="off">
        <p id="search-status" style="margin-top:10px; font-size: 13px; color: #94a3b8;"></p>
    </div>

    <div id="results-container">
        <div class="empty-msg">Öğrenci isminin bir kısmını yazarak başlayın...</div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('live-search');
    const resultsContainer = document.getElementById('results-container');
    const statusText = document.getElementById('search-status');

    searchInput.addEventListener('input', function() {
        let query = this.value.trim().toLowerCase();

        if (query.length < 2) {
            resultsContainer.innerHTML = '<div class="empty-msg">En az 2 harf yazın...</div>';
            statusText.innerText = '';
            return;
        }

        fetch(`{{ route('cv.search') }}?query=${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = '';
            
            if (data.length === 0) {
                statusText.innerText = 'Sonuç bulunamadı.';
                resultsContainer.innerHTML = '<div class="empty-msg">❌ Kayıt bulunamadı.</div>';
                return;
            }

            const grouped = {};
            data.forEach(cv => {
                if (!grouped[cv.ad_soyad]) {
                    grouped[cv.ad_soyad] = [];
                }
                grouped[cv.ad_soyad].push(cv);
            });

            statusText.innerText = `${Object.keys(grouped).length} farklı kişi bulundu.`;

            for (const name in grouped) {
                const cvs = grouped[name];
                const groupHtml = `
                    <div class="name-group">
                        <div class="group-header" onclick="toggleAccordion(this)">
                            <div class="student-name">
                                👤 ${name} 
                                <span class="cv-count">${cvs.length} Özgeçmiş</span>
                            </div>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="cv-list">
                            ${cvs.map(cv => `
                                <div class="cv-item">
                                    <div class="cv-info">
                                        <strong>📄 ${cv.sablon.toUpperCase()} Şablonu</strong>
                                        <span>Güncelleme: ${new Date(cv.updated_at).toLocaleDateString('tr-TR')}</span>
                                    </div>
                                    <a href="/cv/goster/${cv.id}" class="btn-view">CV'yi İncele</a>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                resultsContainer.insertAdjacentHTML('beforeend', groupHtml);
            }
        });
    });

    // DAHA SAĞLAM ACCORDION FONKSİYONU
    function toggleAccordion(element) {
        // Tıklanan header'ın parent'ını (name-group) bul
        const group = element.parentElement;
        
        // Bu klasörü aç/kapat (toggle)
        group.classList.toggle('active');
    }
</script>

</body>
</html>