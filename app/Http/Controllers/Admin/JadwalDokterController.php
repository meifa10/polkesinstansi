<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalDokterController extends Controller
{
    /**
     * Tampilkan halaman jadwal dokter
     */
    public function index()
    {
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');

        // Ambil semua jadwal beserta relasi dokter
        $jadwal = JadwalDokter::with('dokter')->get();

        // Tambahkan properti buka_hari_ini
        foreach ($jadwal as $j) {
            $j->buka_hari_ini = str_contains($j->hari, $hariIni)
                                && $j->status === 'aktif';
        }

        // Ambil semua user role dokter untuk dropdown tambah
        $dokter = User::where('role', 'dokter')->get();

        return view('admin.jadwal.index', compact('jadwal', 'dokter'));
    }


    /**
     * Simpan jadwal baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'poli' => 'required|string|max:255',
            'hari' => 'required|array',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        JadwalDokter::create([
            'dokter_id' => $request->dokter_id,
            'poli' => $request->poli,
            'hari' => implode(',', $request->hari),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }


    /**
     * Toggle status aktif / nonaktif
     */
    public function toggle($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        $jadwal->status = $jadwal->status === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $jadwal->save();

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }
}