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
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ], [
            'hari.required'      => 'Pilih minimal satu hari praktik.',
            'jam_selesai.after'  => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        JadwalDokter::create([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => implode(', ', $request->hari), 
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => 'aktif',
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

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

 
    public function toggle($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        $jadwal->status = ($jadwal->status === 'aktif') ? 'nonaktif' : 'aktif';
        $jadwal->save();

        $pesan = $jadwal->status === 'aktif' 
                 ? 'Jadwal diaktifkan kembali.' 
                 : 'Jadwal berhasil dinonaktifkan.';

        return redirect()->back()->with('success', $pesan);
    }
}