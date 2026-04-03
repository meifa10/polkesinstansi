<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokter Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-emerald-700 min-h-screen text-white flex flex-col shadow-lg">

    <!-- HEADER + LOGO -->
    <div class="p-6 border-b border-emerald-600 text-center">

        <!-- LOGO -->
        <div class="flex justify-center mb-3">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo Instansi"
                 class="w-20 h-20 object-contain bg-white rounded-full p-2 shadow-md">
        </div>

        <h2 class="text-lg font-bold tracking-wide">Panel Dokter</h2>
        <p class="text-sm opacity-80">{{ auth()->user()->name }}</p>
    </div>

    <!-- MENU -->
    <nav class="flex-1 p-4 space-y-2 text-sm">

        <a href="{{ route('dokter.dashboard') }}" class="menu-link">
            Dashboard
        </a>

        <a href="{{ route('dokter.pasien') }}" class="menu-link">
            Daftar Pasien
        </a>

        <a href="{{ route('dokter.rekammedis') }}" class="menu-link">
            Rekam Medis
        </a>

        <a href="{{ route('dokter.profil') }}" class="menu-link">
            Profil Dokter
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
<main class="flex-1 p-6">
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