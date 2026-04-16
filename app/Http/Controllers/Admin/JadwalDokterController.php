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
     * Menampilkan daftar jadwal
     */
    public function index()
    {
        // Mendapatkan nama hari ini dalam bahasa Indonesia
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');

        // Mengambil semua jadwal beserta relasi dokter
        $jadwal = JadwalDokter::with('dokter')->get();

        foreach ($jadwal as $j) {
            // Cek apakah hari ini termasuk dalam jadwal praktik dokter tersebut
            $j->buka_hari_ini = str_contains($j->hari, $hariIni) 
                                && $j->status === 'aktif';
        }

        // Mengambil daftar user yang memiliki role dokter untuk dropdown di modal
        $dokter = User::where('role', 'dokter')->get();

        return view('admin.jadwal.index', compact('jadwal', 'dokter'));
    }

    /**
     * Menyimpan jadwal baru (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:users,id',
            'poli'        => 'required|string|max:255',
            'hari'        => 'required|array|min:1', 
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ], [
            'hari.required'      => 'Pilih minimal satu hari praktik.',
            'jam_selesai.after'  => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        JadwalDokter::create([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => implode(', ', $request->hari), // Mengubah array hari jadi string
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    /**
     * Mengupdate jadwal yang sudah ada (Edit)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:users,id',
            'poli'        => 'required|string|max:255',
            'hari'        => 'required|array|min:1', 
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal = JadwalDokter::findOrFail($id);
        
        $jadwal->update([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => implode(', ', $request->hari),
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    /**
     * Mengubah status aktif/nonaktif (Toggle)
     */
    public function toggle($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        // Switch status
        $jadwal->status = ($jadwal->status === 'aktif') ? 'nonaktif' : 'aktif';
        $jadwal->save();

        $pesan = $jadwal->status === 'aktif' 
                 ? 'Jadwal diaktifkan kembali.' 
                 : 'Jadwal berhasil dinonaktifkan.';

        return redirect()->back()->with('success', $pesan);
    }
}