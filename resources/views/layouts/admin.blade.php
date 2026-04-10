<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Polkes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex">

    <!-- SIDEBAR (FIXED) -->
    <aside class="w-64 bg-emerald-700 text-white flex flex-col 
                  shadow-lg fixed left-0 top-0 h-screen">

        <!-- HEADER + LOGO -->
        <div class="p-6 text-center border-b border-emerald-600">

            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo Polkes"
                     class="w-20 h-20 object-contain bg-white rounded-full p-2 shadow-md">
            </div>

            <h2 class="text-lg font-bold tracking-wide">POLKES JOMBANG</h2>
            <p class="text-sm opacity-80">Admin Panel</p>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">

            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                Dashboard
            </a>

            <a href="{{ route('admin.pendaftaran.index') }}" class="menu-link">
                Pendaftaran Pasien
            </a>

            <a href="{{ route('admin.data_pasien.index') }}" class="menu-link">
                Data Pasien
            </a>

            <a href="{{ route('admin.jadwal_dokter') }}" class="menu-link">
                Data Dokter
            </a>

            <a href="{{ route('admin.pemeriksaan') }}" class="menu-link">
                Pemeriksaan
            </a>

            <a href="{{ route('admin.pembayaran') }}" class="menu-link">
                Pembayaran
            </a>

            <a href="{{ route('admin.laporan') }}" class="menu-link">
                Laporan
            </a>

        </nav>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('instansi.logout') }}" 
              class="p-4 border-t border-emerald-600">
            @csrf
            <button class="w-full bg-red-500 hover:bg-red-600 py-2 rounded text-sm transition">
                Logout
            </button>
        </form>

    </aside>

    <!-- CONTENT (SCROLLABLE) -->
    <main class="flex-1 ml-64 h-screen overflow-y-auto p-8 bg-gray-100">
        @yield('content')
    </main>

    <!-- STYLE -->
    <style>
        .menu-link {
            display: block;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255,255,255,0.1);
            transition: 0.3s ease;
        }

        .menu-link:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(3px);
        }
    </style>

</body>
</html>