<div class="p-6 md:p-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Info & Status -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Status Dokumen</h4>
                
                <div class="flex items-center gap-3 mb-4">
                    @if($sp2d->status == 'Draft')
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-sm">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-700 dark:text-slate-300">Draft</p>
                            <p class="text-xs text-slate-500">Belum diajukan</p>
                        </div>
                    @elseif($sp2d->status == 'Menunggu Verifikasi')
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-sm">
                            <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                        </div>
                        <div>
                            <p class="font-bold text-amber-700 dark:text-amber-400">Menunggu ACC</p>
                            <p class="text-xs text-amber-600/70 dark:text-amber-400/70">Perlu ditinjau admin</p>
                        </div>
                    @elseif($sp2d->status == 'Terverifikasi')
                        <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-sm text-emerald-600 dark:text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-emerald-700 dark:text-emerald-400">Terverifikasi</p>
                            <p class="text-xs text-emerald-600/70 dark:text-emerald-400/70">{{ \Carbon\Carbon::parse($sp2d->verified_at)->translatedFormat('d M Y H:i') }}</p>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-sm text-red-600 dark:text-red-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-red-700 dark:text-red-400">Ditolak</p>
                            <p class="text-xs text-red-600/70 dark:text-red-400/70">{{ \Carbon\Carbon::parse($sp2d->verified_at)->translatedFormat('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>

                @if($sp2d->status == 'Menunggu Verifikasi' && in_array(auth()->user()->role, ['admin', 'atasan']))
                    <div class="pt-4 mt-2 border-t border-slate-200 dark:border-slate-700/50 space-y-2">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Tindakan Admin & Atasan:</p>
                        <button onclick="updateSp2dStatus({{ $sp2d->id }}, 'Terverifikasi')" class="w-full py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-medium transition-all active:scale-95 flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Verifikasi SP2D
                        </button>
                        <button onclick="updateSp2dStatus({{ $sp2d->id }}, 'Ditolak')" class="w-full py-2.5 px-4 bg-white dark:bg-slate-800 border-2 border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl font-medium transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak SP2D
                        </button>
                    </div>
                @endif
                
                @if($sp2d->status == 'Terverifikasi')
                    <div class="pt-4 mt-2 border-t border-slate-200 dark:border-slate-700/50">
                        <a href="{{ route('sp2d.print-receipt', $sp2d->id) }}" target="_blank" class="w-full py-2.5 px-4 bg-white dark:bg-slate-800 border-2 border-blue-100 dark:border-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-xl font-medium transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Tanda Terima
                        </a>
                    </div>
                @endif
            </div>

            <!-- Identitas Card -->
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Informasi Dokumen</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Nomor SP2D</p>
                        <p class="font-bold text-slate-800 dark:text-white">{{ $sp2d->nomor_sp2d }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal SP2D</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($sp2d->tanggal_sp2d)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Nomor SPM Induk</p>
                        @if($sp2d->spm)
                            <a href="{{ route('spm.index') }}?show={{ $sp2d->spm_id }}" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">{{ $sp2d->spm->nomor_spm }}</a>
                        @else
                            <p class="font-medium text-slate-500 italic">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Satuan Kerja (Satker)</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ ($sp2d->spm && $sp2d->spm->satker) ? $sp2d->spm->satker->nama_satker : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Pejabat Pembuat Komitmen (PPK)</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ ($sp2d->spm && $sp2d->spm->ppk) ? $sp2d->spm->ppk->nama : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Keterangan / Uraian</p>
                        <p class="font-medium text-slate-800 dark:text-white text-sm leading-relaxed">{{ $sp2d->keterangan ?: '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="bg-white dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Riwayat Sistem</h4>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Diunggah oleh</p>
                            <p class="font-medium text-sm text-slate-800 dark:text-white">{{ $sp2d->uploader ? $sp2d->uploader->name : 'Unknown' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $sp2d->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @if($sp2d->verified_by)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Diverifikasi oleh</p>
                            <p class="font-medium text-sm text-slate-800 dark:text-white">{{ $sp2d->verifier->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($sp2d->verified_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Nilai & Dokumen -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Value Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 opacity-10">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.97-1.3-3.15-3.15-3.15V4h-1.5v2.53c-1.7.27-3.04 1.29-3.04 2.93 0 1.95 1.62 2.65 3.52 3.12 1.84.46 2.37 1.09 2.37 1.84 0 .9-.85 1.58-2.22 1.58-1.5 0-2.19-.8-2.27-1.87h-1.75c.1 2.05 1.48 3.12 3.52 3.12V20h1.5v-2.58c1.78-.28 3.17-1.34 3.17-3.02 0-2.24-1.84-2.9-3.56-3.32z"/></svg>
                </div>
                
                <div class="relative z-10">
                    <p class="text-emerald-100 font-medium mb-1 tracking-wide">NILAI DANA CAIR (SP2D)</p>
                    <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-2">Rp {{ number_format($sp2d->nilai_sp2d, 0, ',', '.') }}</h2>
                    @if($sp2d->spm)
                        <div class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Nominal SPM: Rp {{ number_format($sp2d->spm->nilai_spm, 0, ',', '.') }}
                        </div>
                        @if($sp2d->nilai_sp2d != $sp2d->spm->nilai_spm)
                            <div class="mt-2 text-sm bg-red-500/80 px-3 py-1.5 rounded-lg inline-block">
                                <span class="font-bold">Perhatian:</span> Nilai SP2D berbeda dengan nilai SPM induk.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- PDF Viewer -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col h-[600px]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex items-center justify-between">
                    <h4 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Dokumen SP2D Resmi
                    </h4>
                    <a href="{{ route('sp2d.file', $sp2d->id) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Buka Penuh
                    </a>
                </div>
                
                <div class="flex-1 bg-slate-100 dark:bg-slate-900 p-2 relative group">
                    @if($sp2d->file_pdf)
                        <iframe src="{{ route('sp2d.file', $sp2d->id) }}#view=FitH" class="w-full h-full rounded-xl border border-slate-200 dark:border-slate-700 shadow-inner" frameborder="0"></iframe>
                        
                        <!-- Overlay action (opsional, muncul saat hover) -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <a href="{{ route('sp2d.file', $sp2d->id) }}" target="_blank" class="pointer-events-auto flex items-center gap-2 px-6 py-3 bg-white text-slate-800 rounded-full font-bold shadow-lg hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800 text-slate-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="font-medium">File PDF tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
