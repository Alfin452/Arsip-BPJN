<div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700 mb-6">
    <h3 class="font-bold text-2xl text-slate-900 dark:text-white">
        Detail Dokumen SPM - <span class="text-blue-600">{{ $spm->nomor_spm }}</span>
    </h3>
    <div class="flex items-center space-x-3">
        @if(auth()->user()->role == 'admin' && in_array($spm->status, ['Draft', 'Menunggu Verifikasi']))
            <button type="button" onclick="updateSpmStatus({{ $spm->id }}, 'Terverifikasi')" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-emerald-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Verifikasi Dokumen
            </button>
            <button type="button" onclick="rejectSpm({{ $spm->id }})" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-red-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Tolak / Revisi
            </button>
        @endif
        @if($spm->status == 'Terverifikasi' && !$spm->sp2d)
        <a href="{{ route('sp2d.create', ['spm_id' => $spm->id]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-emerald-500/30">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
            Buat SP2D
        </a>
        @endif
        @if($spm->status == 'Terverifikasi' || $spm->status == 'Tercairkan (SP2D)')
        <a href="{{ route('spm.print-receipt', $spm->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all shadow-md shadow-blue-500/30">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Tanda Terima
        </a>
        @endif
        <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-6 pr-2">
    <!-- Informasi Metadata -->
    <div class="w-full md:w-1/3 space-y-6">
        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 p-6">
            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Metadata SPM
            </h4>
            
            <dl class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Nomor SPM</dt>
                    <dd>{{ $spm->nomor_spm }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Tanggal</dt>
                    <dd>{{ \Carbon\Carbon::parse($spm->tanggal_spm)->translatedFormat('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Jenis & Tahun</dt>
                    <dd>{{ $spm->jenis_spm }} (TA. {{ $spm->tahun_anggaran }})</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Satker</dt>
                    <dd>{{ $spm->satker->kode_satker ?? '-' }} - {{ $spm->satker->nama_satker ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">PPK</dt>
                    <dd>{{ $spm->ppk->nama ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Nilai (Rp)</dt>
                    <dd class="text-indigo-600 dark:text-indigo-400 font-bold">Rp {{ number_format($spm->nilai_spm, 2, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-800 dark:text-gray-200">Status</dt>
                    <dd>
                        @php
                            $color = 'bg-gray-100 text-gray-800';
                            if($spm->status == 'Draft') $color = 'bg-gray-100 text-gray-800';
                            if($spm->status == 'Menunggu Verifikasi') $color = 'bg-yellow-100 text-yellow-800';
                            if($spm->status == 'Terverifikasi') $color = 'bg-green-100 text-green-800';
                            if($spm->status == 'Ditolak') $color = 'bg-red-100 text-red-800';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ $spm->status }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700 p-6">
            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Status
            </h4>
            <div class="space-y-4">
                @forelse($spm->histories as $history)
                    <div class="text-sm border-l-2 border-indigo-200 pl-3">
                        <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $history->status }}</div>
                        <div class="text-xs text-gray-500">{{ $history->created_at->format('d M Y H:i') }} - {{ $history->user->name ?? 'Sistem' }}</div>
                        @if($history->catatan)
                            <div class="mt-1 p-2 bg-gray-50 dark:bg-gray-900 rounded italic text-gray-600 dark:text-gray-400">"{{ $history->catatan }}"</div>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- PDF Viewer with Tabs -->
    <div class="w-full md:w-2/3 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col shadow-sm">
        <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 overflow-x-auto custom-scrollbar">
            @php 
                $types = ['spm' => 'SPM', 'kuitansi' => 'Kuitansi', 'surat_tugas' => 'Surat Tugas', 'laporan' => 'BAST', 'dokumentasi' => 'Dokumentasi'];
            @endphp
            @foreach($types as $key => $label)
                @php
                    // Define classes for active and inactive states
                    $activeClass = "border-blue-500 text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-800";
                    $inactiveClass = "border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300";
                    $currentClass = ($key === 'spm') ? $activeClass : $inactiveClass;
                @endphp
                <button type="button" data-target="{{ $key }}"
                        class="modal-tab-btn px-6 py-4 border-b-2 font-bold text-base whitespace-nowrap transition-colors outline-none focus:outline-none flex-1 text-center min-w-[120px] {{ $currentClass }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
        
        <div class="flex-1 min-h-[600px] p-0 bg-slate-100 dark:bg-slate-900 rounded-b-2xl">
            @foreach($types as $key => $label)
                @php 
                    $attachment = $spm->attachments->where('tipe_file', $key)->first();
                    $fileExists = $attachment && file_exists(storage_path('app/public/' . $attachment->file_path));
                    $display = ($key === 'spm') ? 'flex' : 'none';
                @endphp
                <div id="tab-content-{{ $key }}" style="display: {{ $display }};" class="modal-tab-content w-full h-full min-h-[600px] flex-col">
                    @if($attachment && $fileExists)
                        @if(Str::endsWith($attachment->file_path, ['.jpg', '.jpeg', '.png']))
                            <div class="w-full flex-1 flex items-center justify-center p-4">
                                <img src="{{ route('spm.file', $attachment->id) }}" alt="{{ $label }}" class="max-w-full max-h-full object-contain rounded-xl shadow">
                            </div>
                        @else
                            <iframe src="{{ route('spm.file', $attachment->id) }}" class="w-full flex-1 border-0" style="min-height: 600px;" title="{{ $label }}"></iframe>
                        @endif
                    @elseif($attachment && !$fileExists)
                        <div class="w-full flex-1 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 space-y-3 bg-red-50/50 dark:bg-red-900/10 p-8">
                            <svg class="w-16 h-16 text-red-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="font-medium text-sm text-red-500 text-center">Data file tercatat, namun fisik file<br><strong>{{ $label }}</strong> tidak ditemukan di server.</p>
                            <p class="text-xs text-slate-400">Silakan unggah ulang dokumen ini.</p>
                        </div>
                    @else
                        <div class="w-full flex-1 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 space-y-3 p-8">
                            <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="font-medium text-sm">File {{ $label }} tidak dilampirkan.</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

