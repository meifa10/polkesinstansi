<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalDokterController extends Controller
{

    public function index()
    {
        $hariIni = Carbon::now()->locale('id')->translatedFormat('l');

        $jadwal = JadwalDokter::with('dokter')->get();

        foreach ($jadwal as $j) {
            $j->buka_hari_ini = str_contains($j->hari, $hariIni)
                                && $j->status === 'aktif';
        }

        $dokter = User::where('role', 'dokter')->get();

        return view('admin.jadwal.index', compact('jadwal', 'dokter'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:users,id',
            'poli'        => 'required|string|max:255',
            'hari'        => 'required|array|min:1', 
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'hari.required'        => 'Silakan pilih minimal satu hari praktik.',
            'jam_selesai.after'    => 'Jam selesai harus lebih besar dari jam mulai.',
            'dokter_id.required'   => 'Nama dokter wajib dipilih.',
        ]);

        
        $hariString = implode(', ', $request->hari);

        JadwalDokter::create([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => $hariString,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil ditambahkan ke sistem.');
    }

    /**
     * Toggle status aktif / nonaktif
     */
    public function toggle($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        // Logika perubahan status
        $jadwal->status = ($jadwal->status === 'aktif') ? 'nonaktif' : 'aktif';
        $jadwal->save();

        $pesan = $jadwal->status === 'aktif' 
                 ? 'Jadwal dokter kini telah diaktifkan.' 
                 : 'Jadwal dokter kini telah dinonaktifkan.';

        return redirect()->back()->with('success', $pesan);
    }
}