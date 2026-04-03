@extends('layouts.dokter')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-8">

    <div class="profile-card w-full max-w-2xl p-10 space-y-8">

        <!-- HEADER -->
        <div class="text-center space-y-3">

            <!-- Avatar -->
            <div class="avatar mx-auto">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ auth()->user()->name }}
                </h1>
                <p class="text-gray-500 text-sm capitalize">
                    {{ auth()->user()->role }}
                </p>
            </div>

        </div>

        <!-- INFO GRID -->
        <div class="grid md:grid-cols-2 gap-6">

            <div class="info-box">
                <p>Email</p>
                <h2>{{ auth()->user()->email }}</h2>
            </div>

            <div class="info-box">
                <p>Status Akun</p>
                <h2 class="text-green-600">Aktif</h2>
            </div>

            <div class="info-box">
                <p>Bergabung Sejak</p>
                <h2>
                    {{ auth()->user()->created_at->translatedFormat('d F Y') }}
                </h2>
            </div>

        <div class="info-box">
            <p>Kode Dokter</p>
            <h2>
                PKJ-DR-{{ str_pad(auth()->user()->id, 3, '0', STR_PAD_LEFT) }}
            </h2>
        </div>

        </div>

        <!-- BUTTON -->
        <div class="text-center pt-4">
            <a href="{{ route('dokter.dashboard') }}"
               class="btn-kembali">
                ← Kembali ke Dashboard
            </a>
        </div>

    </div>

</div>


<style>

/* Main Card */
.profile-card {
    background: white;
    border-radius: 30px;
    box-shadow: 0 25px 70px rgba(0,0,0,0.08);
}

/* Avatar */
.avatar {
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    font-size: 36px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Info Box */
.info-box {
    background: #f9fafb;
    padding: 20px;
    border-radius: 18px;
    transition: 0.3s ease;
}

.info-box:hover {
    transform: translateY(-4px);
    background: #f3f4f6;
}

.info-box p {
    font-size: 13px;
    color: #6b7280;
}

.info-box h2 {
    margin-top: 4px;
    font-weight: 600;
    color: #111827;
}

/* Button */
.btn-kembali {
    display: inline-block;
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    padding: 10px 24px;
    border-radius: 999px;
    font-weight: 600;
    transition: 0.3s ease;
}

.btn-kembali:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}

</style>

@endsection