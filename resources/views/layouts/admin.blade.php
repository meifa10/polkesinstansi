<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Polkes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-emerald-700 text-white flex flex-col">
        <div class="p-6 text-center border-b border-emerald-600">
            <h2 class="text-lg font-bold">POLKES JOMBANG</h2>
            <p class="text-sm opacity-80">Admin Panel</p>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">Dashboard</a>
            <a href="{{ route('admin.pendaftaran.index') }}" class="menu-link">
                Pendaftaran Pasien
            </a>
            <a href="{{ route('admin.data_pasien.index') }}" class="menu-link">Data Pasien</a>
            <a href="{{ route('admin.jadwal_dokter') }}" class="menu-link">Jadwal Dokter</a>
            <a href="{{ route('admin.pemeriksaan') }}" class="menu-link">Pemeriksaan</a>
            <a href="{{ route('admin.pembayaran') }}" class="menu-link">Pembayaran</a>
            <a href="{{ route('admin.laporan') }}" class="menu-link">Laporan</a>
        </nav>

        <form method="POST" action="{{ route('instansi.logout') }}" class="p-4 border-t border-emerald-600">
            @csrf
            <button class="w-full bg-red-500 hover:bg-red-600 py-2 rounded text-sm">
                Logout
            </button>
        </form>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>

    <style>
        .menu-link {
            display: block;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255,255,255,0.1);
        }
        .menu-link:hover {
            background: rgba(255,255,255,0.25);
        }
    </style>

</body>
</html>
