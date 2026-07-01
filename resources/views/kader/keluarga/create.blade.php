<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Keluarga TP PKK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .step-active { display: block; animation: fadeIn 0.4s ease-in-out; }
        .step-hidden { display: none; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-floating {
            position: relative;
        }
        .input-floating input, .input-floating select {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem 0.75rem 0.5rem 0.75rem;
            width: 100%;
            background: #f9fafb;
            transition: all 0.2s;
        }
        .input-floating input:focus, .input-floating select:focus {
            outline: none;
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .input-floating label {
            position: absolute;
            top: 0.5rem;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #6b7280;
            transition: all 0.2s;
            pointer-events: none;
            font-weight: 500;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-gray-800 antialiased h-screen flex overflow-hidden bg-gray-50">

<!-- SIDEBAR TEMPLATE (Hanya Tampilan/Mockup) -->
<aside class="w-72 bg-white border-r border-gray-200 hidden md:flex flex-col flex-shrink-0 shadow-sm z-20">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <div class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-blue-600">SIDA Dompas</div>
    </div>
    <div class="p-4 flex-1 overflow-y-auto">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 mt-2 px-3">Menu Utama</div>
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
            <i class="fa-solid fa-users-viewfinder w-5 text-center"></i> Data Dasawisma
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl bg-indigo-50 text-indigo-700 transition-colors mb-1">
            <i class="fa-solid fa-users w-5 text-center"></i> Data Keluarga
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors ml-6">
            <i class="fa-solid fa-list w-4 text-center"></i> Daftar KK
        </a>
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-indigo-600 bg-white shadow-sm border border-indigo-100 transition-colors ml-6 relative">
            <div class="absolute left-0 w-1 h-full bg-indigo-600 rounded-r-full"></div>
            <i class="fa-solid fa-plus w-4 text-center"></i> Tambah KK Baru
        </a>
    </div>
</aside>

<!-- MAIN CONTENT AREA -->
<main class="flex-1 flex flex-col h-screen overflow-hidden relative">
    <!-- TOPBAR TEMPLATE -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 z-10">
        <div class="flex items-center gap-4">
            <button class="md:hidden text-gray-500 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-800 hidden sm:block">Tambah Keluarga (KK)</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                    K
                </div>
                <div class="hidden sm:block text-sm font-medium text-gray-700">Kader Dasawisma</div>
            </div>
        </div>
    </header>

    <!-- FORM CONTENT -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8 relative w-full">
        
        <div class="max-w-5xl mx-auto">
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Formulir Pendataan Keluarga TP PKK</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data keluarga dan anggota dengan benar.</p>
                </div>
                <a href="{{ route('kader.keluarga.index') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Terdapat error pada pengisian formulir:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- STEPPER INDICATOR -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <!-- Progress Line Background -->
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 rounded-full z-0"></div>
                    <!-- Progress Line Active -->
                    <div id="progress-bar" class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-indigo-600 rounded-full z-0 transition-all duration-500 ease-in-out" style="width: 0%;"></div>
                    
                    <!-- Steps -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-1" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-indigo-600 text-white shadow-md transition-colors duration-300 ring-4 ring-gray-50">
                            1
                        </div>
                        <span class="mt-2 text-xs font-semibold text-indigo-700 bg-gray-50 px-1">Data Wilayah</span>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-2" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-500 transition-colors duration-300 ring-4 ring-gray-50">
                            2
                        </div>
                        <span id="text-2" class="mt-2 text-xs font-semibold text-gray-500 bg-gray-50 px-1">Rekap Keluarga</span>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div id="indicator-3" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-500 transition-colors duration-300 ring-4 ring-gray-50">
                            3
                        </div>
                        <span id="text-3" class="mt-2 text-xs font-semibold text-gray-500 bg-gray-50 px-1">Warga PKK</span>
                    </div>
                </div>
            </div>

            <!-- FORM START -->
            <form id="multi-step-form" action="{{ route('kader.keluarga.store') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf

                <!-- ================= STEP 1 ================= -->
                <div id="step-1" class="step-active p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-indigo-50 text-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-map-location-dot text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Identitas Wilayah & Keluarga</h3>
                            <p class="text-sm text-gray-500">Masukkan data pokok kartu keluarga dan wilayah.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="input-floating">
                            <label>No. Kartu Keluarga <span class="text-red-500">*</span></label>
                            <input type="text" name="no_kk" id="no_kk" value="{{ old('no_kk') }}" required maxlength="16" placeholder="Masukkan 16 digit No KK" class="font-mono mt-1">
                        </div>
                        <div class="input-floating lg:col-span-2">
                            <label>Nama Kepala Rumah Tangga <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kepala_keluarga" id="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga') }}" required placeholder="Nama lengkap sesuai KK" class="mt-1">
                        </div>
                        
                        <div class="input-floating">
                            <label>Dasawisma <span class="text-red-500">*</span></label>
                            <select name="dasawisma_id" id="dasawisma_id" required class="mt-1">
                                <option value="">-- Pilih Dasawisma --</option>
                                @foreach($dasawismas as $ds)
                                    <option value="{{ $ds->id }}" {{ old('dasawisma_id') == $ds->id ? 'selected' : '' }}>{{ $ds->nama_dasawisma }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-4">
                            <div class="input-floating w-1/2">
                                <label>RT</label>
                                <input type="text" name="rt" value="{{ old('rt') }}" placeholder="RT" class="mt-1">
                            </div>
                            <div class="input-floating w-1/2">
                                <label>RW</label>
                                <input type="text" name="rw" value="{{ old('rw') }}" placeholder="RW" class="mt-1">
                            </div>
                        </div>
                        <div class="input-floating">
                            <label>Dusun / Lingkungan</label>
                            <input type="text" name="dusun_lingkungan" value="{{ old('dusun_lingkungan') }}" class="mt-1">
                        </div>

                        <div class="input-floating">
                            <label>Desa</label>
                            <input type="text" name="desa" value="{{ old('desa', 'Dompas') }}" class="mt-1 bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div class="input-floating">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', 'Bukit Batu') }}" class="mt-1 bg-gray-100 text-gray-500" readonly>
                        </div>
                        <div class="input-floating">
                            <label>Kab/Kota & Provinsi</label>
                            <input type="text" value="{{ old('kabupaten', 'Bengkalis') }}, {{ old('provinsi', 'Riau') }}" class="mt-1 bg-gray-100 text-gray-500" readonly>
                            <input type="hidden" name="kabupaten" value="{{ old('kabupaten', 'Bengkalis') }}">
                            <input type="hidden" name="provinsi" value="{{ old('provinsi', 'Riau') }}">
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 2 ================= -->
                <div id="step-2" class="step-hidden p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-blue-50 text-blue-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-chart-pie text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Rekapitulasi Keluarga</h3>
                            <p class="text-sm text-gray-500">Jumlah status dan kategori dalam keluarga.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <!-- Card-style inputs for numbers -->
                        @php
                            $rekap = [
                                ['name' => 'jumlah_kk', 'label' => 'Jml KK', 'default' => 1, 'icon' => 'fa-users'],
                                ['name' => 'jumlah_balita', 'label' => 'Balita', 'default' => 0, 'icon' => 'fa-baby'],
                                ['name' => 'jumlah_pus', 'label' => 'Pasangan Usia Subur', 'default' => 0, 'icon' => 'fa-venus-mars'],
                                ['name' => 'jumlah_wus', 'label' => 'Wanita Usia Subur', 'default' => 0, 'icon' => 'fa-person-dress'],
                                ['name' => 'jumlah_buta', 'label' => '3 Buta', 'default' => 0, 'icon' => 'fa-eye-slash'],
                                ['name' => 'jumlah_ibu_hamil', 'label' => 'Ibu Hamil', 'default' => 0, 'icon' => 'fa-person-pregnant'],
                                ['name' => 'jumlah_ibu_menyusui', 'label' => 'Ibu Menyusui', 'default' => 0, 'icon' => 'fa-person-breastfeeding'],
                                ['name' => 'jumlah_lansia', 'label' => 'Lansia', 'default' => 0, 'icon' => 'fa-person-cane'],
                            ];
                        @endphp

                        @foreach($rekap as $item)
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-indigo-300 transition-colors group">
                            <i class="fa-solid {{ $item['icon'] }} text-gray-400 group-hover:text-indigo-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">{{ $item['label'] }}</label>
                            <input type="number" name="{{ $item['name'] }}" value="{{ old($item['name'], $item['default']) }}" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white p-2 font-mono text-lg font-semibold text-indigo-700">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= STEP 3 ================= -->
                <div id="step-3" class="step-hidden p-6 md:p-8 bg-gray-50/50">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-50 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-users-viewfinder text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Detail Anggota Warga PKK</h3>
                                <p class="text-sm text-gray-500">Masukkan detail tiap anggota keluarga.</p>
                            </div>
                        </div>
                        <button type="button" onclick="tambahAnggota()" class="inline-flex items-center justify-center gap-2 bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                            <i class="fa-solid fa-user-plus"></i> Tambah Anggota
                        </button>
                    </div>
                    
                    <div id="container-anggota" class="space-y-6">
                        <!-- Data form anggota dirender via JS -->
                    </div>
                </div>

                <!-- FORM FOOTER (Navigation Buttons) -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 flex items-center justify-between">
                    <button type="button" id="btn-prev" onclick="changeStep(-1)" class="hidden items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" id="btn-next" onclick="changeStep(1)" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition-colors shadow-md">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button type="submit" id="btn-submit" class="hidden items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md transform hover:scale-[1.02]">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data KK
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</main>

<!-- JavaScript untuk Multi-step dan Dinamis Form -->
<script>
    let currentStep = 1;
    const totalSteps = 3;

    function validateStep(step) {
        if (step === 1) {
            const noKk = document.getElementById('no_kk').value;
            const namaKrt = document.getElementById('nama_kepala_keluarga').value;
            const dasawisma = document.getElementById('dasawisma_id').value;
            
            if (!noKk || !namaKrt || !dasawisma) {
                alert('Mohon lengkapi field yang ditandai bintang (*) pada Data Wilayah sebelum melanjutkan.');
                return false;
            }
            if (noKk.length !== 16) {
                alert('Nomor KK harus tepat 16 digit.');
                return false;
            }
        }
        return true;
    }

    function changeStep(direction) {
        if (direction === 1 && !validateStep(currentStep)) return;

        document.getElementById(`step-${currentStep}`).classList.remove('step-active');
        document.getElementById(`step-${currentStep}`).classList.add('step-hidden');
        
        currentStep += direction;
        
        document.getElementById(`step-${currentStep}`).classList.remove('step-hidden');
        document.getElementById(`step-${currentStep}`).classList.add('step-active');
        
        updateUI();
    }

    function updateUI() {
        // Update Buttons
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');

        if (currentStep === 1) {
            btnPrev.classList.add('hidden');
            btnPrev.classList.remove('inline-flex');
        } else {
            btnPrev.classList.remove('hidden');
            btnPrev.classList.add('inline-flex');
        }

        if (currentStep === totalSteps) {
            btnNext.classList.add('hidden');
            btnNext.classList.remove('inline-flex');
            btnSubmit.classList.remove('hidden');
            btnSubmit.classList.add('inline-flex');
        } else {
            btnNext.classList.remove('hidden');
            btnNext.classList.add('inline-flex');
            btnSubmit.classList.add('hidden');
            btnSubmit.classList.remove('inline-flex');
        }

        // Update Progress Bar
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progress-bar').style.width = `${progress}%`;

        // Update Indicators
        for (let i = 1; i <= totalSteps; i++) {
            const indicator = document.getElementById(`indicator-${i}`);
            const text = document.getElementById(`text-${i}`);
            
            if (i < currentStep) {
                // Completed step
                indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-indigo-600 text-white shadow-md transition-colors duration-300 ring-4 ring-gray-50';
                indicator.innerHTML = '<i class="fa-solid fa-check"></i>';
                if(text) { text.className = 'mt-2 text-xs font-semibold text-indigo-700 bg-gray-50 px-1'; }
            } else if (i === currentStep) {
                // Active step
                indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-indigo-600 text-white shadow-md transition-colors duration-300 ring-4 ring-indigo-50';
                indicator.innerHTML = i;
                if(text) { text.className = 'mt-2 text-xs font-bold text-indigo-700 bg-gray-50 px-1'; }
            } else {
                // Future step
                indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-500 transition-colors duration-300 ring-4 ring-gray-50';
                indicator.innerHTML = i;
                if(text) { text.className = 'mt-2 text-xs font-semibold text-gray-500 bg-gray-50 px-1'; }
            }
        }
    }

    // ==========================================
    // LOGIC ANGGOTA KELUARGA (DYNAMIC)
    // ==========================================
    let anggotaIndex = 0;

    function getAnggotaTemplate(index) {
        return `
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative group anggota-row opacity-0 transform translate-y-4 transition-all duration-300">
                <button type="button" onclick="hapusAnggota(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 p-2.5 rounded-xl transition-all border border-transparent hover:border-red-200 shadow-sm" title="Hapus Anggota">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                
                <div class="flex items-center mb-6 pb-4 border-b border-gray-100 gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm anggota-number">${index+1}</div>
                    <span class="text-sm font-bold text-gray-800 uppercase tracking-wide anggota-badge">Anggota Keluarga</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <!-- Data Pokok -->
                    <div class="input-floating">
                        <label>No. Registrasi</label>
                        <input type="text" name="anggota[${index}][no_reg]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>NIK (No KTP) <span class="text-red-500">*</span></label>
                        <input type="text" name="anggota[${index}][nik]" required maxlength="16" class="mt-1 font-mono">
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="anggota[${index}][nama_anggota]" required class="mt-1">
                    </div>

                    <div class="input-floating">
                        <label>Status Dlm Keluarga <span class="text-red-500">*</span></label>
                        <select name="anggota[${index}][status_dalam_keluarga]" required class="mt-1">
                            <option value="Kepala Rumah Tangga">Kepala Rumah Tangga</option>
                            <option value="Anggota Keluarga" selected>Anggota Keluarga</option>
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Status Perkawinan</label>
                        <select name="anggota[${index}][status_perkawinan]" class="mt-1">
                            <option value="Lajang">Lajang</option>
                            <option value="Menikah">Menikah</option>
                            <option value="Janda">Janda</option>
                            <option value="Duda">Duda</option>
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="anggota[${index}][jenis_kelamin]" required class="mt-1">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Agama</label>
                        <select name="anggota[${index}][agama]" class="mt-1">
                            <option value="Islam">Islam</option>
                            <option value="Kristen Protestan">Kristen Protestan</option>
                            <option value="Kristen Katolik">Kristen Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    
                    <div class="input-floating">
                        <label>Tempat Lahir</label>
                        <input type="text" name="anggota[${index}][tempat_lahir]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="anggota[${index}][tanggal_lahir]" required class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Umur</label>
                        <input type="number" name="anggota[${index}][umur]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Jabatan PKK</label>
                        <input type="text" name="anggota[${index}][jabatan]" class="mt-1">
                    </div>

                    <!-- Pendidikan & Pekerjaan -->
                    <div class="input-floating md:col-span-2">
                        <label>Pendidikan Terakhir</label>
                        <select name="anggota[${index}][pendidikan_terakhir]" class="mt-1">
                            <option value="">- Pilih -</option>
                            <option value="Tidak Tamat SD">Tidak Tamat SD</option>
                            <option value="SD/MI">SD/MI</option>
                            <option value="SMP">SMP</option>
                            <option value="SMU/SMK">SMU/SMK</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Pekerjaan Utama</label>
                        <input type="text" name="anggota[${index}][pekerjaan_utama]" class="mt-1">
                    </div>

                    <!-- Data Wilayah Individu -->
                    <div class="md:col-span-4 mt-6 border-t border-gray-100 pt-6">
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-map-pin text-indigo-500"></i> Alamat & Identitas Wilayah</span>
                    </div>
                    <div class="input-floating">
                        <label>Dasa Wisma</label>
                        <input type="text" name="anggota[${index}][dasa_wisma]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Kepala Rumah Tangga</label>
                        <input type="text" name="anggota[${index}][nama_kepala_rumah_tangga]" class="mt-1">
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Alamat / Jalan</label>
                        <input type="text" name="anggota[${index}][alamat_jalan]" class="mt-1">
                    </div>
                    <div class="flex gap-2">
                        <div class="input-floating w-1/2">
                            <label>RT</label>
                            <input type="text" name="anggota[${index}][rt]" class="mt-1">
                        </div>
                        <div class="input-floating w-1/2">
                            <label>RW</label>
                            <input type="text" name="anggota[${index}][rw]" class="mt-1">
                        </div>
                    </div>
                    <div class="input-floating">
                        <label>Desa/Kelurahan</label>
                        <input type="text" name="anggota[${index}][desa_kelurahan]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Kecamatan</label>
                        <input type="text" name="anggota[${index}][kecamatan]" class="mt-1">
                    </div>
                    <div class="input-floating">
                        <label>Kab/Kota</label>
                        <input type="text" name="anggota[${index}][kabupaten_kota]" class="mt-1">
                    </div>
                    
                    <!-- Data PKK Khusus -->
                    <div class="md:col-span-4 mt-6 border-t border-emerald-100 pt-6">
                        <span class="text-sm font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50 px-3 py-1.5 rounded-lg flex items-center gap-2 inline-flex"><i class="fa-solid fa-leaf"></i> Data Khusus Warga PKK</span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Akseptor KB?</label>
                        <select onchange="toggleField(this, 'kb_wrapper_${index}')" name="anggota[${index}][akseptor_kb]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <div id="kb_wrapper_${index}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Akseptor KB</label>
                            <input type="text" name="anggota[${index}][jenis_akseptor_kb]" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Aktif Posyandu?</label>
                        <select onchange="toggleField(this, 'posyandu_wrapper_${index}')" name="anggota[${index}][aktif_posyandu]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <div id="posyandu_wrapper_${index}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Frekuensi Posyandu</label>
                            <input type="text" name="anggota[${index}][frekuensi_posyandu]" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut Kelompok Belajar?</label>
                        <select onchange="toggleField(this, 'belajar_wrapper_${index}')" name="anggota[${index}][ikut_kelompok_belajar]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <div id="belajar_wrapper_${index}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Paket Belajar</label>
                            <select name="anggota[${index}][jenis_paket_belajar]" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                <option value="">-Pilih-</option>
                                <option value="Paket A">Paket A</option>
                                <option value="Paket B">Paket B</option>
                                <option value="Paket C">Paket C</option>
                                <option value="KF">KF</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut Koperasi?</label>
                        <select onchange="toggleField(this, 'koperasi_wrapper_${index}')" name="anggota[${index}][ikut_koperasi]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                        <div id="koperasi_wrapper_${index}" class="hidden mt-3 pt-3 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Koperasi</label>
                            <input type="text" name="anggota[${index}][jenis_koperasi]" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <!-- Boolean fields only -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Bina Keluarga Balita?</label>
                        <select name="anggota[${index}][ikut_bina_keluarga_balita]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Memiliki Tabungan?</label>
                        <select name="anggota[${index}][memiliki_tabungan]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut PAUD Sejenis?</label>
                        <select name="anggota[${index}][ikut_paud_sejenis]" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    }

    function updateBadges() {
        const numbers = document.querySelectorAll('.anggota-number');
        const badges = document.querySelectorAll('.anggota-badge');
        numbers.forEach((num, index) => {
            num.innerText = (index + 1);
        });
        badges.forEach((badge, index) => {
            if(index === 0) {
                badge.innerText = 'Kepala Keluarga (Wajib)';
                badge.classList.add('text-indigo-700');
            } else {
                badge.innerText = 'Anggota Keluarga';
                badge.classList.remove('text-indigo-700');
            }
        });
    }

    function toggleField(selectElem, targetId) {
        const target = document.getElementById(targetId);
        if (selectElem.value == '1') {
            target.classList.remove('hidden');
        } else {
            target.classList.add('hidden');
            const inputs = target.querySelectorAll('input, select');
            inputs.forEach(i => i.value = '');
        }
    }

    function tambahAnggota() {
        const container = document.getElementById('container-anggota');
        const template = getAnggotaTemplate(anggotaIndex);
        
        container.insertAdjacentHTML('beforeend', template);
        const newlyAdded = container.lastElementChild;
        
        setTimeout(() => {
            newlyAdded.classList.remove('opacity-0', 'translate-y-4');
        }, 10);
        
        // Auto-fill some fields if this is the first member (Kepala Keluarga)
        if(anggotaIndex === 0) {
            setTimeout(() => {
                const kkName = document.getElementById('nama_kepala_keluarga').value;
                if(kkName) {
                    const firstMemberName = document.querySelector(`input[name="anggota[0][nama_anggota]"]`);
                    if(firstMemberName) firstMemberName.value = kkName;
                }
                const firstMemberStatus = document.querySelector(`select[name="anggota[0][status_dalam_keluarga]"]`);
                if(firstMemberStatus) firstMemberStatus.value = 'Kepala Rumah Tangga';
            }, 50);
        }

        anggotaIndex++;
        updateBadges();
    }

    function hapusAnggota(button) {
        const rows = document.querySelectorAll('.anggota-row');
        if (rows.length > 1) {
            const row = button.closest('.anggota-row');
            row.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                row.remove();
                updateBadges();
            }, 300);
        } else {
            alert('Minimal harus ada 1 anggota (Kepala Keluarga)!');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        tambahAnggota(); // Initialize first member
        updateUI(); // Set correct button visibility
    });
</script>
</body>
</html>
