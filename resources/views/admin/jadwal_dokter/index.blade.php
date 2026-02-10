@extends('layouts.admin')

@section('content')

@php
/**
 * FORMAT HARI PRAKTIK
 * Senin,Selasa,Rabu        -> Senin–Rabu
 * Senin,Selasa,Rabu,Sabtu -> Senin–Rabu, Sabtu
 */
function formatHari($hariString)
{
    $urutan = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

    $hari = explode(',', $hariString);
    $index = array_map(fn($h) => array_search($h, $urutan), $hari);
    sort($index);

    $ranges = [];
    $start = $prev = $index[0];

    for ($i = 1; $i < count($index); $i++) {
        if ($index[$i] === $prev + 1) {
            $prev = $index[$i];
        } else {
            $ranges[] = [$start, $prev];
            $start = $prev = $index[$i];
        }
    }
    $ranges[] = [$start, $prev];

    return collect($ranges)->map(function ($r) use ($urutan) {
        return $r[0] === $r[1]
            ? $urutan[$r[0]]
            : $urutan[$r[0]] . '–' . $urutan[$r[1]];
    })->implode(', ');
}
@endphp

<div class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Jadwal Dokter</h1>
            <p class="text-gray-500">Kelola jadwal praktik dokter</p>
        </div>

        <button onclick="openTambah()"
            class="bg-gradient-to-r from-emerald-500 to-green-600
                   text-white px-5 py-2 rounded-xl shadow-lg
                   hover:scale-105 transition">
            + Tambah Jadwal
        </button>
    </div>

    {{-- ================= LIST JADWAL ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jadwal as $j)
        <div class="bg-white rounded-2xl p-6 shadow-xl
            border-l-4 {{ $j->status === 'aktif' ? 'border-emerald-500' : 'border-red-500' }}">

            <div class="flex justify-between mb-3">
                <div>
                    <h2 class="font-bold text-lg text-gray-800">
                        {{ $j->dokter?->name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ $j->poli }}</p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                    {{ $j->status === 'aktif'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ strtoupper($j->status) }}
                </span>
            </div>

            <div class="text-sm text-gray-600 space-y-1">
                <p>📅 {{ formatHari($j->hari) }}</p>
                <p>⏰ {{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</p>
            </div>

            <div class="flex gap-2 mt-5">
                <button
                    onclick="openEdit({{ $j }})"
                    class="flex-1 bg-blue-500 hover:bg-blue-600
                           text-white py-2 rounded-lg text-sm">
                    Edit
                </button>

                <form method="POST"
                      action="{{ route('admin.jadwal_dokter.toggle', $j->id) }}"
                      class="flex-1">
                    @csrf
                    <button class="w-full bg-red-500 hover:bg-red-600
                                   text-white py-2 rounded-lg text-sm">
                        {{ $j->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>
        @empty
            <p class="text-gray-500">Belum ada jadwal dokter</p>
        @endforelse
    </div>
</div>

{{-- ================= MODAL TAMBAH & EDIT ================= --}}
<div id="modal"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

<form method="POST" id="formJadwal"
      class="bg-white rounded-2xl p-6 w-full max-w-lg space-y-4">
    @csrf
    <input type="hidden" name="_method" id="method" value="POST">

    <h2 id="modalTitle" class="text-xl font-bold text-gray-800">
        Tambah Jadwal
    </h2>

    {{-- DOKTER --}}
    <select name="dokter_id" id="dokter"
            class="w-full border p-2 rounded-lg" required>
        <option value="">Pilih Dokter</option>
        @foreach($dokter as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
        @endforeach
    </select>

    {{-- POLI --}}
    <select name="poli" id="poli"
            class="w-full border p-2 rounded-lg" required>
        <option value="Poli Umum">Poli Umum</option>
        <option value="Poli Gigi">Poli Gigi</option>
        <option value="Poli KIA & KB">Poli KIA & KB</option>
    </select>

    {{-- HARI --}}
    <div>
        <p class="text-sm font-semibold mb-2">Hari Praktik</p>
        <div class="grid grid-cols-3 gap-2">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="hari[]" value="{{ $h }}"
                       class="hari rounded">
                {{ $h }}
            </label>
            @endforeach
        </div>
    </div>

    {{-- JAM --}}
    <div class="flex gap-2">
        <input type="time" name="jam_mulai" id="mulai"
               class="border p-2 rounded-lg w-full" required>
        <input type="time" name="jam_selesai" id="selesai"
               class="border p-2 rounded-lg w-full" required>
    </div>

    {{-- ACTION --}}
    <div class="flex gap-2 pt-2">
        <button
            class="flex-1 bg-emerald-600 hover:bg-emerald-700
                   text-white py-2 rounded-lg">
            Simpan
        </button>
        <button type="button" onclick="closeModal()"
            class="flex-1 bg-gray-300 py-2 rounded-lg">
            Batal
        </button>
    </div>
</form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function openTambah() {
    resetForm();
    formJadwal.action = "{{ route('admin.jadwal_dokter.store') }}";
    method.value = "POST";
    modalTitle.innerText = "Tambah Jadwal";
    modal.classList.remove('hidden');
}

function openEdit(j) {
    resetForm();
    formJadwal.action = `/admin/jadwal-dokter/${j.id}`;
    method.value = "PUT";
    modalTitle.innerText = "Edit Jadwal";

    dokter.value = j.dokter_id;
    poli.value = j.poli;
    mulai.value = j.jam_mulai;
    selesai.value = j.jam_selesai;

    j.hari.split(',').forEach(h => {
        document.querySelectorAll('.hari').forEach(c => {
            if (c.value === h) c.checked = true;
        });
    });

    modal.classList.remove('hidden');
}

function closeModal() {
    modal.classList.add('hidden');
}

function resetForm() {
    formJadwal.reset();
    document.querySelectorAll('.hari').forEach(c => c.checked = false);
}
</script>

@endsection
