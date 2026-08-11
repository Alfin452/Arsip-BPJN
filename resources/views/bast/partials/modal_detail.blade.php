<div class="flex flex-col h-[90vh]">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between shrink-0">
        <div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">Detail BAST & Penagihan</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">No. {{ $bast->nomor_bast }}</p>
        </div>
        
        <div class="flex items-center gap-3 pr-12"> <!-- pr-12 to make room for absolute close button -->
            @if(auth()->user()->role === 'admin' && $bast->status == 'Menunggu Verifikasi')
            <form action="{{ route('basts.updateStatus', $bast->id) }}" method="POST" class="inline-flex gap-2">
                @csrf
                <input type="hidden" name="status" value="Terverifikasi">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-0.5">
                    Verifikasi BAST
                </button>
            </form>
            <form action="{{ route('basts.updateStatus', $bast->id) }}" method="POST" class="inline-flex gap-2">
                @csrf
                <input type="hidden" name="status" value="Ditolak">
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 text-sm font-bold rounded-xl transition-all" onclick="return confirm('Tolak dokumen BAST ini?')">
                    Tolak
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Body Layout -->
    <div class="flex-1 overflow-hidden flex flex-col lg:flex-row bg-slate-50 dark:bg-slate-900">
        
        <!-- Left Side: Data Metadata (Scrollable) -->
        <div class="w-full lg:w-[400px] xl:w-[450px] overflow-y-auto border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
            <div class="p-6 space-y-8">
                
                <!-- Status Badge -->
                <div class="flex items-center gap-4 p-4 rounded-2xl {{ $bast->status == 'Terverifikasi' ? 'bg-emerald-50 dark:bg-emerald-900/20' : ($bast->status == 'Ditolak' ? 'bg-red-50 dark:bg-red-900/20' : 'bg-amber-50 dark:bg-amber-900/20') }}">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $bast->status == 'Terverifikasi' ? 'bg-emerald-100 text-emerald-600' : ($bast->status == 'Ditolak' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600') }}">
                        @if($bast->status == 'Terverifikasi')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($bast->status == 'Ditolak')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider {{ $bast->status == 'Terverifikasi' ? 'text-emerald-700' : ($bast->status == 'Ditolak' ? 'text-red-700' : 'text-amber-700') }}">Status Dokumen</p>
                        <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $bast->status }}</p>
                    </div>
                </div>

                <!-- Informasi Penagihan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Tagihan</h4>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-4 border border-blue-100 dark:border-blue-900/30">
                        <p class="text-xs text-blue-600/70 dark:text-blue-400/70 uppercase tracking-wide font-bold mb-1">Total Nilai Penagihan</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-400 font-mono tracking-tight">Rp {{ number_format($bast->nilai_penagihan, 0, ',', '.') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Tanggal BAST</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($bast->tanggal_bast)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Tanggal Tagihan</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $bast->tanggal_penagihan ? \Carbon\Carbon::parse($bast->tanggal_penagihan)->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nomor Penagihan / Invoice</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $bast->nomor_penagihan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Paket Pekerjaan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Kontrak</h4>
                    
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nama Paket Pekerjaan</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ $bast->paketPekerjaan->nama_paket }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nomor Kontrak</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $bast->paketPekerjaan->nomor_kontrak }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nilai Kontrak</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200 font-mono">Rp {{ number_format($bast->paketPekerjaan->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pihak Terkait -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-2">Pihak Terkait</h4>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Penyedia Jasa (Kontraktor)</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $bast->paketPekerjaan->penyedia->nama_perusahaan }}</p>
                            <p class="text-xs text-slate-500">Dir: {{ $bast->paketPekerjaan->penyedia->nama_direktur }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Pejabat Pembuat Komitmen (PPK)</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $bast->paketPekerjaan->ppk->nama }}</p>
                            <p class="text-xs text-slate-500">Satker: {{ $bast->paketPekerjaan->satker->nama_satker }}</p>
                        </div>
                    </div>
                </div>

                <!-- Histori & Jejak Digital -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-2">Jejak Digital</h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <div class="flex-1">
                                <span class="text-slate-500">Diunggah oleh</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $bast->uploader->name ?? 'Sistem' }}</span>
                            </div>
                            <div class="text-xs text-slate-400">{{ $bast->created_at->format('d/m/y H:i') }}</div>
                        </div>

                        @if($bast->verified_by)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <div class="flex-1">
                                <span class="text-slate-500">Diverifikasi oleh</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $bast->verifier->name ?? '-' }}</span>
                            </div>
                            <div class="text-xs text-slate-400">{{ $bast->verified_at ? $bast->verified_at->format('d/m/y H:i') : '-' }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($bast->keterangan)
                <!-- Keterangan Tambahan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-2">Keterangan</h4>
                    <p class="text-sm text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 p-4 rounded-xl italic">
                        "{{ $bast->keterangan }}"
                    </p>
                </div>
                @endif
                
            </div>
        </div>

        <!-- Right Side: PDF Preview -->
        <div class="flex-1 bg-slate-200 dark:bg-slate-900 flex flex-col relative overflow-hidden">
            @if($bast->file_dokumen)
                <!-- PDF Viewer Toolbar -->
                <div class="h-14 bg-slate-800 flex items-center justify-between px-4 shrink-0 shadow-md z-10 text-slate-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        <span class="text-sm font-medium">Dokumen Resmi BAST</span>
                    </div>
                    <div>
                        <a href="{{ route('basts.file', $bast->id) }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 bg-slate-700 hover:bg-slate-600 rounded text-xs font-bold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Buka di Tab Baru
                        </a>
                    </div>
                </div>
                
                <!-- PDF Iframe -->
                <div class="flex-1 w-full h-full relative bg-slate-100">
                    <iframe 
                        src="{{ route('basts.file', $bast->id) }}#toolbar=0&view=FitH" 
                        class="absolute inset-0 w-full h-full border-0"
                        title="PDF Viewer"
                    ></iframe>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-slate-400 dark:text-slate-600">
                    <svg class="w-24 h-24 mb-4 stroke-slate-300 dark:stroke-slate-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <p class="text-lg font-medium text-slate-500">File Dokumen Tidak Tersedia</p>
                    <p class="text-sm mt-2 max-w-sm text-center text-slate-400">Scan dokumen resmi BAST & Penagihan belum diunggah untuk record ini.</p>
                </div>
            @endif
        </div>
        
    </div>
</div>
