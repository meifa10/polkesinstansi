<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Polkes Instansi</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
  .login .overlay { transform: translateX(0%); }
  .register .overlay { transform: translateX(100%); }

  .login .login-form { opacity:1; transform:translateX(0); pointer-events:auto; }
  .login .register-form { opacity:0; transform:translateX(-40px); pointer-events:none; }

  .register .login-form { opacity:0; transform:translateX(40px); pointer-events:none; }
  .register .register-form { opacity:1; transform:translateX(0); pointer-events:auto; }
</style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-emerald-100">

<div class="container login relative w-[880px] h-[500px] bg-white rounded-3xl shadow-xl overflow-hidden transition-all duration-700">

  <!-- ================= LOGIN ================= -->
  <div class="login-form absolute right-0 w-1/2 h-full flex items-center px-14 transition-all duration-700">
    <div class="w-full">
      <h2 class="text-3xl font-bold text-gray-800 mb-2">Login</h2>
      <p class="text-sm text-gray-500 mb-6">Admin & Dokter</p>

      <!-- 🔥 FORM LOGIN FIX -->
      <form method="POST" action="{{ route('instansi.login.post') }}" class="space-y-4">
        @csrf

        <input 
          type="email" 
          name="email"
          placeholder="Email"
          required
          class="w-full px-5 py-3 rounded-xl bg-gray-100 focus:ring-2 focus:ring-emerald-400 outline-none">

        <div class="relative">
          <input 
            id="loginPassword" 
            type="password" 
            name="password"
            placeholder="Password"
            required
            class="w-full px-5 py-3 pr-12 rounded-xl bg-gray-100 focus:ring-2 focus:ring-emerald-400 outline-none">

          <button type="button"
            onclick="togglePassword('loginPassword')"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600">
            👁
          </button>
        </div>

        <button type="submit"
          class="w-full py-3 rounded-xl bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition">
          Login
        </button>
      </form>

      <p class="text-sm text-gray-500 mt-6">
        Belum punya akun dokter?
        <button onclick="showRegister()" class="text-emerald-600 font-semibold">Daftar</button>
      </p>
    </div>
  </div>

  <!-- ================= REGISTER ================= -->
  <div class="register-form absolute left-0 w-1/2 h-full flex items-center px-14 transition-all duration-700">
    <div class="w-full">
      <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Dokter</h2>
      <p class="text-sm text-gray-500 mb-6">Tenaga Medis</p>

      <form method="POST" action="{{ route('instansi.register') }}" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Nama Dokter" required
          class="w-full px-5 py-3 rounded-xl bg-gray-100 focus:ring-2 focus:ring-emerald-400 outline-none">

        <input type="email" name="email" placeholder="Email" required
          class="w-full px-5 py-3 rounded-xl bg-gray-100 focus:ring-2 focus:ring-emerald-400 outline-none">

        <input type="password" name="password" placeholder="Password" required
          class="w-full px-5 py-3 rounded-xl bg-gray-100 focus:ring-2 focus:ring-emerald-400 outline-none">

        <button type="submit"
          class="w-full py-3 rounded-xl bg-emerald-500 text-white font-semibold hover:bg-emerald-600 transition">
          Daftar Dokter
        </button>
      </form>

      <p class="text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <button onclick="showLogin()" class="text-emerald-600 font-semibold">Login</button>
      </p>
    </div>
  </div>

  <!-- ================= OVERLAY ================= -->
  <div class="overlay absolute top-0 left-0 w-1/2 h-full transition-transform duration-700 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('/images/clinic.jpg')"></div>
    <div class="absolute inset-0 bg-emerald-600/80"></div>

    <div class="relative z-10 h-full flex flex-col items-center justify-start text-white text-center pt-24 px-10">
      <h2 class="text-2xl font-bold mb-3 drop-shadow-lg">
        POLKES 05.09.15 JOMBANG
      </h2>
      <p class="text-sm opacity-95 drop-shadow">
        Sistem Informasi<br>Klinik & Instansi
      </p>
    </div>
  </div>
</div>

<script>
  const container = document.querySelector('.container');

  function showRegister() {
    container.classList.replace('login','register');
  }

  function showLogin() {
    container.classList.replace('register','login');
  }

  function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
  }
</script>

</body>
</html>
