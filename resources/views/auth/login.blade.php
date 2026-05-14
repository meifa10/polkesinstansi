<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Instansi - POLKES JOMBANG</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            overflow: hidden;
        }

        /* Smooth Transition Logic */
        .container-box.register-mode .overlay-container {
            transform: translateX(-100%);
        }

        .container-box.register-mode .form-section {
            transform: translateX(100%);
        }

        .container-box.register-mode .login-box {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.9) translateY(20px);
        }

        .container-box.register-mode .register-box {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1) translateY(0);
        }

        .form-container {
            transition: all 0.7s cubic-bezier(0.7, 0, 0.3, 1);
        }

        .overlay-container, .form-section {
            transition: all 0.7s cubic-bezier(0.7, 0, 0.3, 1);
        }

        /* Glass Input Styling */
        .glass-input {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            border: 1.5px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-input:focus {
            background: white;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            transform: translateY(-1px);
        }

        /* Floating Animation */
        @keyframes floating {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .float-element {
            animation: floating 6s ease-in-out infinite;
        }

        /* Custom Scrollbar for Register Form if needed */
        .register-box::-webkit-scrollbar {
            width: 4px;
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .btn-primary:active {
            transform: scale(0.95);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::after {
            width: 300px;
            height: 300px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Abstract Background Decor -->
    <div class="fixed top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-100/50 blur-[100px] -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-blue-100/50 blur-[100px] -z-10"></div>

    <div class="container-box relative w-full max-w-[1100px] h-[720px] bg-white/70 backdrop-blur-2xl rounded-[48px] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.15)] overflow-hidden flex border border-white/50">

        <!-- FORM SECTION (LEFT SIDE BY DEFAULT) -->
        <div class="form-section relative w-1/2 h-full z-10">
            
            <!-- LOGIN BOX -->
            <div class="form-container login-box absolute inset-0 flex flex-col justify-center px-16 lg:px-24">
                <div class="mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-[11px] font-bold tracking-widest uppercase mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Portal Akses Instansi
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Selamat Datang <br><span class="text-emerald-600">Kembali.</span>
                    </h2>
                    <p class="text-slate-500 mt-3 font-medium">Silakan masuk untuk mengelola data sistem.</p>
                </div>

                <form method="POST" action="{{ route('instansi.login.post') }}" class="space-y-6" autocomplete="off">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-400 ml-1 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" placeholder="nama@polkes.com" required
                            class="glass-input w-full px-6 py-4 rounded-2xl outline-none text-slate-700 font-semibold placeholder:text-slate-300">
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest">Password</label>
                            <a href="#" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <input id="loginPass" type="password" name="password" placeholder="••••••••" required
                                class="glass-input w-full px-6 py-4 rounded-2xl outline-none text-slate-700 font-semibold pr-14 placeholder:text-slate-300">
                            <button type="button" onclick="togglePassword('loginPass')" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors">
                                <svg id="icon-loginPass" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 rounded-2xl bg-emerald-600 text-white font-bold text-lg shadow-[0_10px_25px_-5px_rgba(16,185,129,0.4)] hover:bg-emerald-700 hover:shadow-emerald-200 mt-2">
                        Masuk Sistem
                    </button>
                </form>

                <div class="mt-12 text-center">
                    <p class="text-sm text-slate-400 font-medium">
                        Belum terdaftar? 
                        <button type="button" onclick="toggleMode()" class="text-emerald-600 font-bold hover:text-emerald-700 underline underline-offset-4 ml-1">Buat Akun</button>
                    </p>
                </div>
            </div>

            <!-- REGISTER BOX -->
            <div class="form-container register-box absolute inset-0 flex flex-col justify-center px-16 lg:px-24 opacity-0 pointer-events-none transform translate-y-10">
                <div class="mb-8">
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">Registrasi <br><span class="text-emerald-600">Personel.</span></h2>
                    <p class="text-slate-500 mt-3 font-medium">Lengkapi data untuk akses petugas medis.</p>
                </div>

                <form method="POST" action="{{ route('instansi.register') }}" class="space-y-4" autocomplete="off">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-extrabold text-slate-400 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="name" placeholder="Dr. John Doe" required class="glass-input w-full px-5 py-3.5 rounded-xl outline-none text-slate-700 font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-extrabold text-slate-400 ml-1 uppercase tracking-widest">Role Instansi</label>
                            <select name="role" required class="glass-input w-full px-5 py-3.5 rounded-xl outline-none text-slate-700 font-semibold appearance-none">
                                <option value="">Pilih Role</option>
                                <option value="dokter">Dokter</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 ml-1 uppercase tracking-widest">Email</label>
                        <input type="email" name="email" placeholder="email@polkes.com" required class="glass-input w-full px-5 py-3.5 rounded-xl outline-none text-slate-700 font-semibold">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold text-slate-400 ml-1 uppercase tracking-widest">Password Baru</label>
                        <input type="password" name="password" placeholder="••••••••" required class="glass-input w-full px-5 py-3.5 rounded-xl outline-none text-slate-700 font-semibold">
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 rounded-2xl bg-emerald-600 text-white font-bold text-lg shadow-[0_10px_25px_-5px_rgba(16,185,129,0.4)] hover:bg-emerald-700 mt-4">
                        Daftarkan Akun
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm text-slate-400 font-medium">
                        Sudah punya akun? 
                        <button type="button" onclick="toggleMode()" class="text-emerald-600 font-bold hover:text-emerald-700 underline underline-offset-4 ml-1">Kembali Login</button>
                    </p>
                </div>
            </div>
        </div>

        <!-- OVERLAY SECTION (RIGHT SIDE BY DEFAULT) -->
        <div class="overlay-container absolute top-0 right-0 w-1/2 h-full z-20">
            <div class="relative h-full w-full bg-slate-900 overflow-hidden">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000" style="background-image:url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=1200&q=80')"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/95 via-emerald-800/80 to-transparent"></div>

                <!-- Animated Elements -->
                <div class="absolute top-20 left-20 w-32 h-32 bg-emerald-400/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-20 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>

                <!-- Content -->
                <div class="relative z-30 h-full flex flex-col items-center justify-center text-white px-16 text-center">
                    <div class="float-element bg-white/10 backdrop-blur-xl p-6 rounded-[32px] mb-10 border border-white/20 shadow-2xl">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-emerald-300">
                            <path d="M11 2a2 2 0 0 0-2 2v5H4a2 2 0 0 0-2 2v2c0 1.1.9 2 2 2h5v5c0 1.1.9 2 2 2h2a2 2 0 0 0 2-2v-5h5a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-5V4a2 2 0 0 0-2-2h-2z"/>
                        </svg>
                    </div>

                    <h1 class="text-4xl font-black tracking-tight mb-4 leading-none uppercase">
                        POLKES <span class="text-emerald-400">JOMBANG</span>
                        <span class="block text-lg font-medium tracking-[0.3em] mt-2 text-emerald-100/70">POLIKLINIK KESEHATAN</span>
                    </h1>

                    <div class="w-20 h-1.5 bg-emerald-500/50 rounded-full mb-8"></div>

                    <p class="text-emerald-50/80 text-sm leading-relaxed max-w-sm font-medium italic">
                        "Mewujudkan sistem informasi kesehatan yang terintegrasi, profesional, dan melayani dengan sepenuh hati."
                    </p>

                    <div class="absolute bottom-10 flex gap-4 opacity-50">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                        <div class="w-2 h-2 rounded-full bg-white/30"></div>
                        <div class="w-2 h-2 rounded-full bg-white/30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Login/Register
        const containerBox = document.querySelector('.container-box');
        function toggleMode() {
            containerBox.classList.toggle('register-mode');
        }

        // Toggle Password Visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        // Clear inputs on load
        window.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('input:not([type="hidden"])')
                .forEach(input => {

                    input.value = '';

                });

        });
    </script>
</body>
</html>