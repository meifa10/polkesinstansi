@extends('layouts.dokter')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

<div class="p-4 md:p-6 lg:p-10 bg-slate-50 min-h-screen font-sans text-slate-900">
    
    <div class="max-w-6xl mx-auto">
        
        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wide mb-3 border border-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Pemeriksaan Medis ({{ $pasien->poli ?? auth()->user()->poli ?? 'Poli Umum' }})
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                    Formulir <span class="text-emerald-600">Pemeriksaan</span>
                </h1>
            </div>
            
            <div class="bg-slate-900 text-white px-5 py-3 rounded-xl flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center text-emerald-400 font-bold text-lg uppercase border border-slate-700">
                    {{ substr($pasien->nama_pasien, 0, 1) }}
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-0.5">Pasien Saat Ini</p>
                    <p class="font-bold text-sm leading-tight truncate max-w-[200px]">{{ $pasien->nama_pasien }}</p>
                </div>
            </div>
        </div>

        {{-- ================= VALIDATION ALERT ================= --}}
        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border border-rose-300 text-rose-800 rounded-xl flex items-start gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-bold mb-0.5">Terjadi Kesalahan</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- ================= MAIN FORM CONTAINER ================= --}}
        <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-300 shadow-sm">
            
            <form method="POST" action="{{ route('dokter.pemeriksaan.store', $pasien->id) }}" class="space-y-10">
                @csrf

                {{-- 1. DATA VITAL (READ ONLY) --}}
                <div>
                    <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Informasi Awal & Vitals
                    </h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                        <div class="lg:col-span-6 grid grid-cols-3 gap-3 md:gap-4">
                            <div class="bg-slate-50 p-3 md:p-4 rounded-xl border border-slate-200 flex flex-col justify-center">
                                <span class="text-[10px] md:text-xs font-semibold text-slate-600 mb-1">Berat Badan</span>
                                <div class="text-lg md:text-xl font-bold text-slate-900 flex items-baseline gap-1">
                                    {{ $pasien->berat_badan }} <span class="text-[10px] md:text-xs text-slate-500 font-medium">KG</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 md:p-4 rounded-xl border border-slate-200 flex flex-col justify-center">
                                <span class="text-[10px] md:text-xs font-semibold text-slate-600 mb-1">Tinggi Badan</span>
                                <div class="text-lg md:text-xl font-bold text-slate-900 flex items-baseline gap-1">
                                    {{ $pasien->tinggi_badan }} <span class="text-[10px] md:text-xs text-slate-500 font-medium">CM</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 md:p-4 rounded-xl border border-slate-200 flex flex-col justify-center">
                                <span class="text-[10px] md:text-xs font-semibold text-slate-600 mb-1">Tensi Darah</span>
                                <div class="text-lg md:text-xl font-bold text-slate-900 flex items-baseline gap-1">
                                    {{ $pasien->tensi ?? '-' }} <span class="text-[10px] md:text-xs text-slate-500 font-medium">mmHg</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-6 bg-amber-50 p-4 rounded-xl border border-amber-200 flex flex-col justify-center">
                            <span class="text-xs font-bold text-amber-800 mb-2 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                Keluhan Pasien
                            </span>
                            <div class="text-amber-900 font-medium text-sm md:text-base leading-relaxed">
                                "{{ $pasien->keluhan }}"
                            </div>
                            <input type="hidden" name="keluhan" value="{{ $pasien->keluhan }}">
                        </div>
                    </div>
                </div>

                {{-- 2. DIAGNOSIS (ICD-10 LARGER DATASET BERDASARKAN POLI) --}}
                <div>
                    <h2 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Diagnosis ICD-10 & Tindakan Medis
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-800">Diagnosis Medis (ICD-10)</label>
                            <div class="relative">
                                <input list="list-icd10" name="diagnosis" required 
                                       value="{{ old('diagnosis') }}" 
                                       placeholder="Ketik kode ICD-10 atau nama penyakit..."
                                       class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all placeholder:text-slate-400">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            @php
                                $currentPoli = $pasien->poli ?? auth()->user()->poli ?? 'Poli Umum';
                            @endphp

                            <datalist id="list-icd10">
                                @if(str_contains(strtolower($currentPoli), 'gigi'))
                                    {{-- ================= POLI GIGI (20 DIAGNOSA POPULER) ================= --}}
                                    <option value="K02.9 - Dental Caries, Unspecified (Gigi Berlubang / Karies Gigi)"></option>
                                    <option value="K04.0 - Pulpitis (Radang Pulpa Akut / Kronis)"></option>
                                    <option value="K04.1 - Necrosis of Pulp (Gigi Mati / Nekrosis Pulpa)"></option>
                                    <option value="K05.3 - Chronic Periodontitis (Radang Jaringan Penyangga Gigi)"></option>
                                    <option value="K05.1 - Chronic Gingivitis (Radang Gusi / Gusi Berdarah)"></option>
                                    <option value="K04.7 - Periapical Abscess without Sinus (Abses Gigi / Gusi Bengkak)"></option>
                                    <option value="K00.6 - Disturbances in Tooth Eruption (Persistensi Gigi / Gigi Susu Enggan Tanggal)"></option>
                                    <option value="K01.1 - Impacted Teeth (Gigi Impaksi / Gigi Geraham Bungsu Terpendam)"></option>
                                    <option value="K12.1 - Other Forms of Stomatitis (Sariawan / Aphthous Stomatitis)"></option>
                                    <option value="K03.6 - Deposits [Accretions] on Teeth (Karang Gigi / Calculus Gigi)"></option>
                                    <option value="K03.0 - Excessive Attrition of Teeth (Gigi Aus / Gigi Attrisi)"></option>
                                    <option value="K03.1 - Abrasion of Teeth (Abrasi Gigi / Sikat Gigi Terlalu Keras)"></option>
                                    <option value="K04.2 - Pulp Degeneration (Degenerasi Pulpa)"></option>
                                    <option value="K04.6 - Periapical Abscess with Sinus (Abses Periapikal dengan Fistula)"></option>
                                    <option value="K06.0 - Gingival Recession (Gusi Melorot / Resesi Gusi)"></option>
                                    <option value="K06.1 - Gingival Enlargement (Hiperplasia Gusi)"></option>
                                    <option value="K07.2 - Anomalies of Dental Arch Relationship (Maloklusi / Gigi Tonggos/Metu)"></option>
                                    <option value="K08.1 - Loss of Teeth Due to Accident, Extraction (Gigi Ompong)"></option>
                                    <option value="K10.2 - Inflammatory Conditions of Jaws (Osteitis / Radang Tulang Rahang)"></option>
                                    <option value="K13.0 - Diseases of Lips (Cheilitis / Bibir Pecah-pecah Kronis)"></option>

                                @elseif(str_contains(strtolower($currentPoli), 'kia') || str_contains(strtolower($currentPoli), 'kb'))
                                    {{-- ================= POLI KIA & KB (20 DIAGNOSA POPULER) ================= --}}
                                    <option value="Z34.9 - Supervision of Normal Pregnancy, Unspecified (ANC / Kontrol Hamil Normal)"></option>
                                    <option value="Z30.0 - General Counseling and Advice on Contraception (Konsultasi Awal KB)"></option>
                                    <option value="Z30.4 - Surveillance of Contraceptive Drugs/Devices (Kontrol KB Suntik/IUD/Implan)"></option>
                                    <option value="O21.0 - Mild Hyperemesis Gravidarum (Mual Muntah Hamil Ringan)"></option>
                                    <option value="N91.2 - Amenorrhoea, Unspecified (Terlambat Haid / Tidak Haid)"></option>
                                    <option value="N92.0 - Excessive and Frequent Menstruation (Darah Haid Berlebih / Menoragia)"></option>
                                    <option value="N94.6 - Dysmenorrhoea, Unspecified (Nyeri Haid Berat)"></option>
                                    <option value="N76.0 - Acute Vaginitis (Keputihan Patologis / Radang Vagina)"></option>
                                    <option value="Z39.2 - Routine Postpartum Follow-Up (Kontrol Nifas / Pasca Melahirkan)"></option>
                                    <option value="O13 - Gestational Hypertension (Hipertensi Kehamilan Tanpa Proteinuria)"></option>
                                    <option value="O14.9 - Preeclampsia, Unspecified (Keracunan Kehamilan / Preeklampsia)"></option>
                                    <option value="O47.0 - False Labour Before 37 Completed Weeks of Gestation (Ancaman Prematur / Palsu)"></option>
                                    <option value="Z00.1 - Routine Child Health Examination (Imunisasi / Tumbuh Kembang Balita)"></option>
                                    <option value="N64.4 - Mastodynia (Nyeri Payudara / Mastalgia)"></option>
                                    <option value="O91.2 - Non-purulent Mastitis Associated with Childbirth (Radang Payudara Ibu Menyusui)"></option>
                                    <option value="D50.9 - Iron Deficiency Anemia, Unspecified (Anemia pada Ibu Hamil)"></option>
                                    <option value="A34 - Obstetrical Tetanus (Kondisi Kebidanan Khusus)"></option>
                                    <option value="E28.2 - Polycystic Ovarian Syndrome (PCOS / Sel Telur Kecil)"></option>
                                    <option value="N85.0 - Endometrial Glandular Hyperplasia (Penebalan Dinding Rahim)"></option>
                                    <option value="Z35.9 - Supervision of High-Risk Pregnancy (Kehamilan Risiko Tinggi)"></option>

                                @else
                                    {{-- ================= POLI UMUM (25 DIAGNOSA POPULER) ================= --}}
                                    <option value="J00 - Acute Nasopharyngitis [Common Cold] (Flu / Batuk Pilek Tradisional)"></option>
                                    <option value="J02.9 - Acute Pharyngitis, Unspecified (Radang Tenggorokan)"></option>
                                    <option value="J03.9 - Acute Tonsillitis, Unspecified (Amdal / Tonsilitis Akut)"></option>
                                    <option value="K30 - Functional Dyspepsia (Sakit Maag / Nyeri Lambung / Gerd)"></option>
                                    <option value="I10 - Essential (Primary) Hypertension (Darah Tinggi / Hipertensi)"></option>
                                    <option value="R50.9 - Fever, Unspecified (Demam Tanpa Penyebab Khusus)"></option>
                                    <option value="M79.1 - Myalgia (Nyeri Otot / Pegal Linu / Badan Kaku)"></option>
                                    <option value="E11.9 - Type 2 Diabetes Mellitus without Complications (Kencing Manis)"></option>
                                    <option value="R51 - Headache (Sakit Kepala / Cephalgia)"></option>
                                    <option value="G43.9 - Migraine, Unspecified (Sakit Kepala Sebelah)"></option>
                                    <option value="A09.9 - Gastroenteritis and Colitis of Infectious Origin (Diare / Muntaber)"></option>
                                    <option value="L23.9 - Allergic Contact Dermatitis, Unspecified (Gatal Alergi Kulit)"></option>
                                    <option value="L30.9 - Dermatitis, Unspecified (Eksem / Radang Kulit)"></option>
                                    <option value="H10.9 - Conjunctivitis, Unspecified (Mata Merah / Belekan)"></option>
                                    <option value="M10.9 - Gout, Unspecified (Asam Urat Akut)"></option>
                                    <option value="E78.0 - Pure Hypercholesterolaemia (Kolesterol Tinggi)"></option>
                                    <option value="J45.9 - Asthma, Unspecified (Asma / Sesak Napas Kambuhan)"></option>
                                    <option value="R05 - Cough (Batuk Saja)"></option>
                                    <option value="Z00.0 - General Medical Examination (Medical Check Up / Surat Sehat)"></option>
                                    <option value="T14.0 - Superficial Injury of Unspecified Body Region (Luka Lecet / Luka Ringan)"></option>
                                    <option value="B35.9 - Dermatophytosis, Unspecified (Jamur Kulit / Kadas / Kurap)"></option>
                                    <option value="I95.9 - Hypotension, Unspecified (Tekanan Darah Rendah)"></option>
                                    <option value="M54.5 - Low Back Pain (Nyeri Punggung Bawah / Boyokan)"></option>
                                    <option value="A01.0 - Typhoid Fever (Demam Tifoid / Tipes)"></option>
                                    <option value="N39.0 - Urinary Tract Infection, Site Not Specified (ISK / Anyang-anyangan)"></option>
                                @endif
                            </datalist>
                        </div>
                        
                        {{-- Field Tindakan Medis --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-800">Tindakan Medis</label>
                            <div class="relative">
                                <select name="tindakan" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-base text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Tindakan Medis --</option>
                                    @foreach($tindakan as $t)
                                        <option value="{{ $t }}" {{ old('tindakan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. RESEP OBAT --}}
                <div class="bg-slate-50 p-5 md:p-6 rounded-2xl border border-slate-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-slate-200 pb-4 mb-5 gap-3">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Resep Obat
                        </h2>
                        <button type="button" onclick="tambahObat()" class="inline-flex items-center justify-center gap-2 bg-white text-emerald-700 hover:bg-emerald-50 px-4 py-2 rounded-lg text-sm font-semibold transition-colors border border-emerald-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Obat
                        </button>
                    </div>

                    <div id="obat-wrapper" class="space-y-4">
                        <div class="obat-row flex flex-col md:flex-row items-start md:items-end gap-4 p-4 bg-white border border-slate-300 rounded-xl relative transition-all">
                            
                            {{-- Pilih Obat --}}
                            <div class="flex-grow w-full md:w-auto space-y-1.5">
                                <label class="block text-sm font-semibold text-slate-800">Nama Obat</label>
                                <div class="relative">
                                    <input list="list-obat" class="search-obat w-full pl-4 pr-10 py-2.5 bg-white border border-slate-300 rounded-lg text-base text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all placeholder:text-slate-400" placeholder="Ketik nama obat..." oninput="syncObatId(this)" required>
                                    <input type="hidden" name="obat_id[]" class="real-id">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Kuantitas & Satuan --}}
                            <div class="w-full md:w-56 space-y-1.5 flex-shrink-0">
                                <label class="block text-sm font-semibold text-slate-800">Jumlah & Satuan</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="qty[]" placeholder="0" class="w-16 md:w-20 px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-base text-slate-900 text-center focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all placeholder:text-slate-400" min="1" required>
                                    <div class="relative flex-1">
                                        <select name="satuan[]" class="w-full pl-3 pr-8 py-2.5 bg-white border border-slate-300 rounded-lg text-sm md:text-base text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all appearance-none cursor-pointer" required>
                                            <option value="Tablet">Tablet</option>
                                            <option value="Kapsul">Kapsul</option>
                                            <option value="Sirup">Sirup</option>
                                            <option value="Puyer">Puyer</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Aturan Pakai --}}
                            <div class="w-full md:w-[35%] space-y-1.5 flex-shrink-0">
                                <label class="block text-sm font-semibold text-slate-800">Aturan Pakai</label>
                                <input type="text" name="aturan_minum[]" placeholder="Cth: 3 x 1 Sesudah Makan" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-lg text-base text-slate-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all placeholder:text-slate-400" required>
                            </div>

                            {{-- Tombol Hapus --}}
                            <button type="button" onclick="hapusObat(this)" class="w-full md:w-auto mt-2 md:mt-0 flex items-center justify-center p-3 bg-white text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200 flex-shrink-0" title="Hapus Obat">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="md:hidden ml-2 text-sm font-semibold">Hapus Obat</span>
                            </button>
                        </div>
                    </div>

                    <datalist id="list-obat">
                        @foreach($obat as $o)
                            <option data-id="{{ $o->id }}" value="{{ $o->nama_obat }}">
                                Stok: {{ $o->stok }} | Harga: Rp {{ number_format($o->harga) }}
                            </option>
                        @endforeach
                    </datalist>
                </div>

                {{-- 4. SUBMIT SECTION --}}
                <div class="pt-6 border-t border-slate-200 flex flex-col items-end gap-2">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-xl text-sm font-bold transition-all shadow-sm">
                        Selesaikan Pemeriksaan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                    <p class="text-slate-500 text-xs font-medium text-center md:text-right w-full md:w-auto">
                        Data otomatis diteruskan ke admin untuk pembayaran.
                    </p>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
    function syncObatId(input) {
        const val = input.value;
        const options = document.getElementById('list-obat').options;
        const hiddenInput = input.parentElement.querySelector('.real-id');
        
        hiddenInput.value = ""; 
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                hiddenInput.value = options[i].getAttribute('data-id');
                break;
            }
        }
    }

    function tambahObat() {
        const wrapper = document.getElementById('obat-wrapper');
        const rows = document.querySelectorAll('.obat-row');
        
        const newRow = rows[0].cloneNode(true);
        
        // Reset inputs to blank
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Reset select explicitly
        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });
        
        wrapper.appendChild(newRow);
    }

    function hapusObat(btn) {
        const rows = document.querySelectorAll('.obat-row');
        if (rows.length > 1) {
            btn.closest('.obat-row').remove();
        } else {
            alert('Minimal harus ada satu baris input obat. Kosongkan isinya saja jika tidak memberikan resep obat.');
        }
    }
</script>

@endsection