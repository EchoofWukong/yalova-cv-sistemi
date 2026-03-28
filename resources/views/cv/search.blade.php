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
            display: none; 
            padding: 15px 25px; 
            background: #fff; 
            border-top: 1px solid #f1f5f9;
        }
        
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
            display: inline-block;
        }
        .btn-view:hover { opacity: 0.8; }

        .empty-msg { text-align: center; color: #94a3b8; padding: 60px 0; }
        .loading { text-align: center; color: #3b82f6; padding: 40px 0; font-weight: bold; }
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
        <div class="loading">Öğrenciler Yükleniyor... ⏳</div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('live-search');
    const resultsContainer = document.getElementById('results-container');
    const statusText = document.getElementById('search-status');

    // LARAVEL'DEN GİRİŞ YAPAN KİŞİNİN ROLÜNÜ VE GÜVENLİK TOKENİNİ ALIYORUZ
    const currentUserRole = "{{ Auth::user()->role ?? '' }}";
    const csrfToken = "{{ csrf_token() }}";

    window.onload = function() {
        fetchStudents(''); 
    };

    searchInput.addEventListener('input', function() {
        let query = this.value.trim().toLowerCase();
        fetchStudents(query);
    });

    function fetchStudents(query) {
        let url = `{{ route('cv.search') }}?query=${query}`;

        fetch(url, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = ''; 
            
            if (data.length === 0) {
                statusText.innerText = 'Sonuç bulunamadı.';
                resultsContainer.innerHTML = '<div class="empty-msg">❌ Öğrenci bulunamadı.</div>';
                return;
            }

            const grouped = {};
            data.forEach(cv => {
                if (!grouped[cv.ad_soyad]) {
                    grouped[cv.ad_soyad] = [];
                }
                grouped[cv.ad_soyad].push(cv);
            });

            statusText.innerText = `${Object.keys(grouped).length} öğrenci listeleniyor.`;

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
                                    <div style="display:flex; align-items:center; gap:5px;">
                                        <a href="/cv/goster/${cv.id}" class="btn-view">CV'yi İncele</a>
                                        
                                        ${currentUserRole === 'admin' ? `
                                            <a href="/cv/duzenle/${cv.id}" class="btn-view" style="background: #f59e0b;">Düzenle</a>
                                            <form action="/cv/sil/${cv.id}" method="POST" style="margin:0;" onsubmit="return confirm('Bu CV kalıcı olarak silinecek. Emin misiniz?');">
                                                <input type="hidden" name="_token" value="${csrfToken}">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn-view" style="background: #ef4444; border:none; cursor:pointer; font-family:inherit;">Sil</button>
                                            </form>
                                        ` : ''}

                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                resultsContainer.insertAdjacentHTML('beforeend', groupHtml);
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            resultsContainer.innerHTML = '<div class="empty-msg">Veriler yüklenirken bir hata oluştu.</div>';
        });
    }

    function toggleAccordion(element) {
        const group = element.parentElement;
        group.classList.toggle('active');
    }
</script>

</body>
</html>