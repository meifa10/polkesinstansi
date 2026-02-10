@extends('layouts.dokter')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-2">Profil Dokter</h1>
    <p class="text-gray-600 mb-6">
        Informasi akun dan data pribadi dokter
    </p>

    <div class="bg-white rounded-xl shadow p-6 max-w-xl">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Nama Dokter
            </label>
            <div class="border rounded-lg px-4 py-2 bg-gray-50">
                {{ auth()->user()->name }}
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Email
            </label>
            <div class="border rounded-lg px-4 py-2 bg-gray-50">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Role
            </label>
            <div class="border rounded-lg px-4 py-2 bg-gray-50 capitalize">
                {{ auth()->user()->role }}
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('dokter.dashboard') }}"
               class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                Kembali ke Dashboard
            </a>
        </div>

    </div>

</div>
@endsection
