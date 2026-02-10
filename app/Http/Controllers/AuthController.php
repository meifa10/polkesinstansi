<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * PROSES LOGIN (ADMIN & DOKTER)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email','password'))) {
            $request->session()->regenerate();

            return match (auth()->user()->role) {
                'admin'  => redirect()->route('admin.dashboard'),
                'dokter' => redirect()->route('dokter.dashboard'),
                default  => $this->logoutAndBlock()
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    /**
     * REGISTER DOKTER (INI YANG KURANG)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'dokter'
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun dokter berhasil dibuat. Silakan login.');
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * BLOK ROLE TIDAK SAH
     */
    private function logoutAndBlock()
    {
        Auth::logout();
        abort(403, 'Akses ditolak');
    }
}
