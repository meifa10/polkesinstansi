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
            overflow: hidden;
        }

        /* Logic Animasi Split Screen */
        .container-box.register-mode .overlay-container { transform: translateX(-100%); }
        .container-box.register-mode .form-container.login-box { transform: translateX(100%); opacity: 0; pointer-events: none; }
        .container-box.register-mode .form-container.register-box { transform: translateX(100%); opacity: 1; pointer-events: auto; }

        .form-container { transition: all 0.8s cubic-bezier(0.645, 0.045, 0.355, 1); }
        .overlay-container { transition: all 0.8s cubic-bezier(0.645, 0.045, 0.355, 1); }

        .float-icon { animation: floating 3s ease-in-out infinite; }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .glass-input {
            background: rgba(243, 244, 246, 0.8);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(229, 231, 235, 0.5);
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            background: white;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#f0f4f8] p-4">

    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-200/40 blur-[120px]"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-200/40 blur-[120px]"></div>

    <div class="container-box relative w-full max-w-[1000px] h-[650px] bg-white/80 backdrop-blur-xl rounded-[40px] shadow-[0_30px_100px_rgba(0,0,0,0.12)] overflow-hidden flex">
        
        <div class="relative w-1/2 h-full z-10">
            
            <div class="form-container login-box absolute inset-0 flex items-center px-12 lg:px-20 opacity-1">
                <div class="w-full">
                    <div class="mb-10">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold tracking-widest uppercase mb-4 font-sans">Portal Instansi</span>
                        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
                        <p class="text-gray-500 mt-2 font-medium">Masuk untuk akses Admin & Dokter</p>
                    </div>

                    <form method="POST" action="{{ route('instansi.login.post') }}" class="space-y-5" autocomplete="off">
                        @csrf <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 ml-1 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" id="loginEmail" placeholder="dokter@polkes.com" required autocomplete="off"
                                class="glass-input clear-input w-full px-6 py-4 rounded-2xl outline-none text-gray-700 font-medium">
                        </div>

                        <div class="space-y-1 relative">
                            <label class="text-xs font-bold text-gray-400 ml-1 uppercase tracking-wider">Security Password</label>
                            <div class="relative">
                                <input id="loginPass" type="password" name="password" placeholder="••••••••" required autocomplete="new-password"
                                    class="glass-input clear-input w-full px-6 py-4 rounded-2xl outline-none text-gray-700 font-medium pr-14">
                                <button type="button" onclick="togglePassword('loginPass')" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-500 transition-colors">
                                    <svg id="icon-loginPass" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-2xl bg-emerald-600 text-white font-bold text-lg shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            Masuk Sekarang
                        </button>
                    </form>

                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <p class="text-sm text-gray-500 text-center">
                            Belum memiliki akun dokter? 
                            <button onclick="toggleMode()" class="text-emerald-600 font-bold hover:underline">Daftar Akun</button>
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-container register-box absolute inset-0 flex items-center px-12 lg:px-20 opacity-0 pointer-events-none">
                <div class="w-full">
                    <div class="mb-8">
                        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Daftar Dokter</h2>
                        <p class="text-gray-500 mt-2 font-medium">Bergabung dengan tim medis</p>
                    </div>

                    <form method="POST" action="{{ route('instansi.register') }}" class="space-y-4" autocomplete="off">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 ml-1 uppercase tracking-wider">Nama Lengkap</label>
                            <input type="text" name="name" placeholder="dr. Jhon Doe, Sp.PD" required autocomplete="off"
                                class="glass-input clear-input w-full px-6 py-4 rounded-2xl outline-none text-gray-700 font-medium">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 ml-1 uppercase tracking-wider">Email Institusi</label>
                            <input type="email" name="email" placeholder="example@polkes.com" required autocomplete="off"
                                class="glass-input clear-input w-full px-6 py-4 rounded-2xl outline-none text-gray-700 font-medium">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 ml-1 uppercase tracking-wider">Create Password</label>
                            <div class="relative">
                                <input id="regPass" type="password" name="password" placeholder="••••••••" required autocomplete="new-password"
                                    class="glass-input clear-input w-full px-6 py-4 rounded-2xl outline-none text-gray-700 font-medium pr-14">
                                <button type="button" onclick="togglePassword('regPass')" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-500 transition-colors">
                                    <svg id="icon-regPass" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-2xl bg-emerald-600 text-white font-bold text-lg shadow-lg hover:bg-emerald-700 transition-all duration-300">
                            Daftar Sekarang
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-sm text-gray-500 text-center">
                            Sudah memiliki akun? 
                            <button onclick="toggleMode()" class="text-emerald-600 font-bold hover:underline">Masuk Login</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overlay-container absolute top-0 right-0 w-1/2 h-full z-20">
            <div class="relative h-full w-full bg-emerald-600 overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center scale-110" 
                    style="background-image:url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1000&q=80')">
                </div>
                <div class="absolute inset-0 bg-gradient-to-tr from-emerald-900/90 via-emerald-800/80 to-emerald-600/40"></div>

                <div class="relative z-30 h-full flex flex-col items-center justify-center text-white px-12 text-center">
                    <div class="float-icon bg-white/20 backdrop-blur-md p-5 rounded-[30px] mb-8 border border-white/30">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-4 leading-tight uppercase">
                        POLKES 05.09.15<br><span class="text-emerald-300">KAB. JOMBANG</span>
                    </h1>
                    <div class="w-16 h-1 bg-emerald-400 rounded-full mb-6"></div>
                    <p class="text-emerald-50 text-sm leading-relaxed opacity-90 italic">
                        "Professional Medical Service & Integrated Healthcare System"
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PERBAIKAN FATAL: Hanya mengosongkan input teks, BUKAN input hidden (CSRF)
        window.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                // Seleksi hanya input yang memiliki class 'clear-input'
                const inputs = document.querySelectorAll('.clear-input');
                inputs.forEach(input => {
                    input.value = '';
                });
            }, 50);
        });

        const containerBox = document.querySelector('.container-box');

        function toggleMode() {
            containerBox.classList.toggle('register-mode');
        }

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
    </script>
</body>
</html>