@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">

<style>
    html, body {
        background-color: #0d121c !important; 
        margin: 0;
        padding: 0;
    }

    .antrian-wrapper {
        width: 100%;
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #064e3b 0%, #0d121c 100%);
        padding: 40px 20px;
    }

    .integrated-ticket {
        background: #ffffff !important;
        width: 100%;
        max-width: 420px;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    .ticket-header {
        background: #ffffff !important;
        padding: 35px 20px;
        text-align: center;
        border-bottom: 2px dashed #cbd5e1;
        position: relative;
    }

    .ticket-header::before, .ticket-header::after {
        content: "";
        position: absolute;
        width: 30px;
        height: 30px;
        background: #081d18; 
        border-radius: 50%;
        bottom: -15px;
    }
    .ticket-header::before { left: -15px; }
    .ticket-header::after { right: -15px; }

    .hospital-identity {
        font-weight: 800;
        font-size: 18px;
        color: #10b981 !important;
        text-transform: uppercase;
    }

    .ticket-display-number {
        font-size: 100px;
        font-weight: 900;
        color: #1e293b !important;
        line-height: 1;
        margin: 10px 0;
    }

    .ticket-body {
        background: #ffffff !important;
        padding: 25px 30px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        color: #64748b !important;
        text-transform: uppercase;
    }

    .detail-item span.text-value {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a !important;
    }

    /* CSS BASE UNTUK BADGE STATUS */
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
    }

    /* WARNA DINAMIS UNTUK MASING-MASING STATUS */
    .status-petugas {
        background: #fef3c7 !important; /* Kuning Amber soft */
        color: #d97706 !important; /* Teks Kuning gelap */
    }
    .status-dokter {
        background: #dbeafe !important; /* Biru soft */
        color: #1d4ed8 !important; /* Teks Biru gelap */
    }
    .status-selesai {
        background: #d1fae5 !important; /* Hijau soft */
        color: #065f46 !important; /* Teks Hijau gelap */
    }

    .btn-action {
        display: block;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        margin-bottom: 10px;
        border: none;
        cursor: pointer;
    }

    .btn-save { background: #10b981; color: white; }
    .btn-dashboard { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .ticket-footer {
        text-align: center;
        font-size: 11px;
        color: #64748b !important;
        margin-top: 15px;
        line-height: 1.5;
    }
</style>

<div class="antrian-wrapper">
    <div class="integrated-ticket" id="capture-zone">
        <div class="ticket-header">
            <div class="hospital-identity">POLKES JOMBANG</div>
            <div style="color: #64748b; font-weight: 700; font-size: 12px; letter-spacing: 1px;">NOMOR ANTRIAN DIGITAL</div>
            <div class="ticket-display-number">
                {{ str_pad($data->nomor_antrian, 2, '0', STR_PAD_LEFT) }}
            </div>
            <div style="color: #334155; font-weight: 600; font-size: 14px;">
                {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="ticket-body">
            <div class="details-grid">
                <div class="detail-item">
                    <label>Nama Pasien</label>
                    <span class="text-value">{{ $data->nama_pasien }}</span>
                </div>
                <div class="detail-item">
                    <label>Layanan Poli</label>
                    <span class="text-value">{{ $data->poli }}</span>
                </div>
                <div class="detail-item">
                    <label>Estimasi Dilayani</label>
                    <span class="text-value" style="color: #10b981 !important;">± {{ $prediksi }} WIB</span>
                </div>
                
                {{-- LOGIKA STATUS YANG DIPERBAIKI --}}
                <div class="detail-item">
                    <label>Status</label>
                    <div>
                        @if($data->status == 'menunggu_petugas')
                            <span class="status-badge status-petugas">ANTRIAN PETUGAS</span>
                        @elseif($data->status == 'diproses_dokter' || $data->status == 'proses')
                            <span class="status-badge status-dokter">ANTRIAN DOKTER</span>
                        @elseif($data->status == 'selesai')
                            <span class="status-badge status-selesai">SELESAI</span>
                        @else
                            {{-- Fallback jika ada status lain --}}
                            <span class="status-badge status-petugas">{{ str_replace('_', ' ', $data->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="no-screenshot">
                <button onclick="saveTicket()" class="btn-action btn-save">
                    SIMPAN ANTRIAN KE GALERI
                </button>
                <a href="{{ route('dashboard') }}" class="btn-action btn-dashboard">
                    KEMBALI KE DASHBOARD
                </a>
            </div>

            <div class="ticket-footer">
                Harap datang 15 menit sebelum estimasi.<br>
                Tunjukkan tiket digital ini kepada petugas poli.
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    function saveTicket() {
        const zone = document.getElementById('capture-zone');
        const noShow = zone.querySelector('.no-screenshot');
        
        noShow.style.visibility = 'hidden'; // Gunakan visibility agar layout tidak bergeser saat capture

        html2canvas(zone, {
            scale: 3, 
            useCORS: true,
            backgroundColor: "#ffffff",
        }).then(canvas => {
            noShow.style.visibility = 'visible';
            const link = document.createElement('a');
            link.download = 'Tiket_Antrian_{{ $data->nomor_antrian }}.png';
            link.href = canvas.toDataURL('image/png', 1.0);
            link.click();
        });
    }
</script>

@endsection