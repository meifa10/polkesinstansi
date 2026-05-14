<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Petugas Panel - Polkes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen overflow-hidden flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-emerald-700 text-white flex flex-col 
                    shadow-lg fixed left-0 top-0 h-screen">

        <!-- HEADER -->
        <div class="p-6 text-center border-b border-emerald-600">

            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo Polkes"
                     class="w-20 h-20 object-contain bg-white rounded-full p-2 shadow-md">
            </div>

            <h2 class="text-lg font-bold tracking-wide">
                POLKES JOMBANG
            </h2>

            <p class="text-sm opacity-80">
                Petugas Panel
            </p>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">

            <a href="{{ route('petugas.dashboard') }}" class="menu-link">
                Dashboard
            </a>

            <a href="{{ route('petugas.pendaftaran.index') }}" class="menu-link">
                Data Pendaftaran
            </a>

            <a href="{{ route('petugas.pemeriksaan_awal.index') }}" class="menu-link">
                Pemeriksaan Awal
            </a>

            <a href="{{ route('petugas.stok_obat.index') }}" class="menu-link">
                Stok Obat
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

    <!-- CONTENT -->
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
            background: rgba(255,255,255,0.12);
            transition: 0.3s ease;
        }

        .menu-link:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(3px);
        }

    </style>

</body>
</html>