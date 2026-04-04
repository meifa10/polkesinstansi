<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <style>
            @layer theme, base, components, utilities;
            /* Tambahkan Tailwind v4 CDN atau tetap gunakan build internal Anda */
            /* Di sini saya asumsikan CSS utama sudah dimuat dari vite/internal */
            
            /* Perbaikan khusus untuk SVG agar tidak overflow di HP */
            .responsive-svg-container svg {
                width: 100% !important;
                height: auto !important;
                max-width: 100%;
            }
        </style>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-4 sm:p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col overflow-x-hidden">
        
        <header class="w-full lg:max-w-4xl max-w-full text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] border rounded-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC]">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] border rounded-sm">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full lg:grow">
            <main class="flex max-w-full w-full flex-col-reverse lg:max-w-4xl lg:flex-row shadow-xl rounded-lg overflow-hidden">
                
                <div class="flex-1 p-8 sm:p-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                    <h1 class="mb-2 text-2xl font-semibold">Selamat Datang</h1>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Masuk untuk akses Admin & Dokter.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Email Address</label>
                            <div class="w-full p-3 bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-lg text-gray-400">dok1</div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Security Password</label>
                            <div class="w-full p-3 bg-gray-50 dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-lg flex justify-between items-center text-gray-400">
                                <span>••••••••</span>
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </div>
                        <button class="w-full sm:w-auto px-8 py-3 bg-[#009661] hover:bg-[#007d51] text-white font-medium rounded-lg transition-all">
                            Masuk Sekarang
                        </button>
                    </div>
                </div>

                <div class="bg-[#2d7a5e] dark:bg-[#1D0002] relative w-full lg:w-[400px] shrink-0 overflow-hidden flex flex-col items-center justify-center p-10 text-white text-center">
                    
                    <div class="responsive-svg-container opacity-20 absolute inset-0 flex items-center justify-center p-4">
                        <svg viewBox="0 0 438 104" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                            <path d="M17.2036 -3H0V102.197H49.5189V86.7187H17.2036V-3Z" fill="white" />
                            <path d="M110.256 41.6337C108.061 38.1275 104.945 35.3731 100.905 33.3681C96.8667 31.3647 92.8016 30.3618 88.7131 30.3618..." fill="white" />
                        </svg>
                    </div>

                    <div class="relative z-10 w-20 h-20 mb-6 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold leading-tight mb-2 uppercase tracking-tighter">Polkes 05.09.15<br>Kab. Jombang</h2>
                        <div class="w-16 h-1 bg-green-400 mx-auto mb-6"></div>
                        <p class="italic text-sm opacity-90 leading-relaxed max-w-[250px]">
                            "Professional Medical Service & Integrated Healthcare System"
                        </p>
                    </div>

                    <div class="absolute inset-0 shadow-[inset_0px_0px_0px_1px_rgba(255,255,255,0.1)]"></div>
                </div>

            </main>
        </div>

    </body>
</html>