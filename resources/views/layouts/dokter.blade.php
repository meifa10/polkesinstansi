<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokter Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-emerald-700 min-h-screen text-white flex flex-col">
    <div class="p-6 border-b border-emerald-600">
        <h2 class="text-lg font-bold">Panel Dokter</h2>
        <p class="text-sm opacity-80">{{ auth()->user()->name }}</p>
    </div>

    <nav class="flex-1 p-4 space-y-2 text-sm">
        <a href="{{ route('dokter.dashboard') }}" class="block px-3 py-2 rounded hover:bg-emerald-600">
            Dashboard
        </a>

        <a href="{{ route('dokter.pasien') }}" class="block px-3 py-2 rounded hover:bg-emerald-600">
            Daftar Pasien
        </a>

        <a href="{{ route('dokter.rekammedis') }}" class="block px-3 py-2 rounded hover:bg-emerald-600">
            Rekam Medis
        </a>

        <a href="{{ route('dokter.profil') }}" class="block px-3 py-2 rounded hover:bg-emerald-600">
            Profil Dokter
        </a>
    </nav>

    <form method="POST" action="{{ route('instansi.logout') }}" class="p-4 border-t border-emerald-600">
        @csrf
        <button class="w-full bg-red-500 hover:bg-red-600 py-2 rounded text-sm">
            Logout
        </button>
    </form>
</aside>

<!-- CONTENT -->
<main class="flex-1 p-6">
    @yield('content')
</main>

</body>
</html>
