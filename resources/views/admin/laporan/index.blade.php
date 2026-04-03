@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="laporan-container">

<!-- HEADER -->
<div class="header-section">

<div>
<h1 class="title-main">Laporan Analitik Pasien</h1>

<div class="subtitle">
<span class="dot"></span>
<span>Periode <strong>{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</strong></span>
</div>
</div>


<form method="GET" class="filter-box">

<select name="bulan">
@for($i=1;$i<=12;$i++)
<option value="{{ sprintf('%02d',$i) }}" {{ $bulan==sprintf('%02d',$i)?'selected':'' }}>
{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
</option>
@endfor
</select>

<input type="number" name="tahun" value="{{ $tahun }}">

<button type="submit" class="btn-primary">
Filter
</button>

<a href="{{ route('admin.laporan.pdf',['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn-secondary">
Export PDF
</a>

</form>

</div>



<!-- KPI -->
<div class="kpi-grid">

<div class="card">

<div class="card-header">
Total Kunjungan
</div>

<div class="card-value">
{{ $totalKunjungan }}
</div>

<div class="card-meta">
<span class="badge-green">BPJS {{ $bpjs }}</span>
<span class="badge-soft">Umum {{ $umum }}</span>
</div>

</div>



<div class="card">

<div class="card-header">
Total Pemasukan
</div>

<div class="card-value">
Rp {{ number_format($totalPemasukan,0,',','.') }}
</div>

<div class="card-meta">

<span class="status-dot green"></span> Lunas {{ $lunas }}

<span class="status-dot yellow"></span> Pending {{ $belumLunas }}

</div>

</div>



<div class="card">

<div class="card-header">
Total Pemeriksaan
</div>

<div class="card-value">
{{ $totalPemeriksaan }}
</div>

<div class="card-meta">
Aktivitas medis bulan ini
</div>

</div>

</div>



<!-- DETAIL -->
<div class="detail-grid">

<div class="panel">

<div class="panel-title">
Distribusi Poli
</div>

@forelse($kunjunganPerPoli as $p)

<div class="progress-item">

<div class="progress-top">

<span>{{ $p->poli }}</span>

<span class="count">{{ $p->total }}</span>

</div>

<div class="progress-bar">

<div class="progress-fill green"
style="width: {{ ($p->total / max(1,$totalKunjungan))*100 }}%"></div>

</div>

</div>

@empty

<div class="empty">Belum ada data kunjungan</div>

@endforelse

</div>



<div class="panel">

<div class="panel-title">
Metode Pembayaran
</div>

@forelse($metodePembayaran as $m)

<div class="progress-item">

<div class="progress-top">

<span>{{ strtoupper($m->paid_by ?? 'LAINNYA') }}</span>

<span class="count">{{ $m->total }}</span>

</div>

<div class="progress-bar">

<div class="progress-fill blue"
style="width: {{ ($m->total / max(1,$totalKunjungan))*100 }}%"></div>

</div>

</div>

@empty

<div class="empty">Belum ada transaksi</div>

@endforelse

</div>

</div>

</div>



<style>

.laporan-container{
font-family:'Plus Jakarta Sans',sans-serif;
padding:24px;
background:#f8fafc;
}


/* HEADER */

.header-section{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.title-main{
font-size:22px;
font-weight:700;
color:#0f172a;
}

.subtitle{
display:flex;
align-items:center;
gap:6px;
font-size:13px;
color:#64748b;
}

.dot{
width:6px;
height:6px;
background:#10b981;
border-radius:50%;
}



/* FILTER */

.filter-box{
display:flex;
gap:10px;
background:white;
padding:10px;
border-radius:12px;
border:1px solid #e2e8f0;
}

.filter-box select,
.filter-box input{
border:1px solid #e2e8f0;
border-radius:8px;
padding:6px 10px;
font-size:13px;
outline:none;
}



/* BUTTON */

.btn-primary{
background:#0f172a;
color:white;
padding:6px 14px;
border-radius:8px;
font-size:13px;
font-weight:600;
}

.btn-secondary{
background:#fee2e2;
color:#991b1b;
padding:6px 14px;
border-radius:8px;
font-size:13px;
font-weight:600;
}



/* KPI */

.kpi-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
gap:16px;
margin-bottom:28px;
}

.card{
background:white;
padding:18px;
border-radius:14px;
border:1px solid #e2e8f0;
}

.card-header{
font-size:13px;
color:#64748b;
margin-bottom:6px;
}

.card-value{
font-size:22px;
font-weight:700;
margin-bottom:8px;
}

.card-meta{
font-size:12px;
color:#64748b;
}

.badge-green{
background:#10b981;
color:white;
padding:3px 8px;
border-radius:6px;
font-size:11px;
}

.badge-soft{
background:#ecfdf5;
color:#065f46;
padding:3px 8px;
border-radius:6px;
font-size:11px;
}



/* STATUS */

.status-dot{
width:7px;
height:7px;
display:inline-block;
border-radius:50%;
margin-right:4px;
}

.green{background:#10b981;}
.yellow{background:#f59e0b;}



/* DETAIL */

.detail-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:18px;
}

.panel{
background:white;
border-radius:14px;
padding:18px;
border:1px solid #e2e8f0;
}

.panel-title{
font-weight:600;
margin-bottom:16px;
font-size:15px;
}



/* PROGRESS */

.progress-item{
margin-bottom:14px;
}

.progress-top{
display:flex;
justify-content:space-between;
font-size:13px;
margin-bottom:4px;
}

.progress-bar{
height:6px;
background:#f1f5f9;
border-radius:8px;
overflow:hidden;
}

.progress-fill{
height:100%;
}

.progress-fill.green{
background:#10b981;
}

.progress-fill.blue{
background:#6366f1;
}

.count{
font-weight:600;
}



/* EMPTY */

.empty{
text-align:center;
color:#94a3b8;
font-size:13px;
padding:20px;
}

</style>

@endsection