<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $jadwal = JadwalDokter::with('dokter')->orderByDesc('id')->get();
        $dokter = User::where('role', 'dokter')->get();

        return view('admin.jadwal_dokter.index', compact('jadwal', 'dokter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id'   => 'required|exists:users,id',
            'poli'        => 'required',
            'hari'        => 'required|array|min:1',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        JadwalDokter::create([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => implode(',', $request->hari),
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status'      => 'aktif',
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dokter_id'   => 'required',
            'poli'        => 'required',
            'hari'        => 'required|array|min:1',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal = JadwalDokter::findOrFail($id);

        $jadwal->update([
            'dokter_id'   => $request->dokter_id,
            'poli'        => $request->poli,
            'hari'        => implode(',', $request->hari),
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil diperbarui');
    }

    public function toggle($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $jadwal->status = $jadwal->status === 'aktif' ? 'nonaktif' : 'aktif';
        $jadwal->save();

        return back();
    }
}
