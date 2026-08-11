<div class="bg-white dark:bg-slate-800 overflow-hidden shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 rounded-3xl relative">
    
    <!-- Skeleton Overlay (Tampil saat AJAX Loading) -->
    <div x-show="isLoading" x-transition.opacity class="absolute inset-0 z-10 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm flex items-center justify-center" style="display: none;">
        <div class="flex items-center gap-3 bg-white dark:bg-slate-800 px-5 py-3 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Memuat Data...</span>
        </div>
    </div>

    <div class="overflow-x-auto" :class="{ 'opacity-50 blur-[2px] pointer-events-none': isLoading }">
        <table class="w-full text-left border-collapse transition-all duration-300">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs tracking-wider border-b border-slate-100 dark:border-slate-700">
                    <th class="p-5 font-semibold w-12 text-center">No.</th>
                    <th class="p-5 font-semibold">SPM & Tanggal</th>
                    <th class="p-5 font-semibold">Instansi (Satker)</th>
                    <th class="p-5 font-semibold text-right">Nilai (Rp)</th>
                    <th class="p-5 font-semibold text-center">Status</th>
                    <th class="p-5 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse ($spms as $index => $spm)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="p-5 text-sm text-center text-slate-500 dark:text-slate-400">
                            {{ $spms->firstItem() + $index }}
                        </td>
                        <td class="p-5">
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $spm->nomor_spm }}</p>
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        {{ $spm->jenis_spm }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($spm->tanggal_spm)->format('d M Y') }}</p>
                            </div>
                        </td>
                        <td class="p-5">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $spm->satker->nama_satker ?? '-' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Kode: {{ $spm->satker->kode_satker ?? '-' }}</p>
                        </td>
                        <td class="p-5 text-sm font-bold text-right text-slate-900 dark:text-white tabular-nums">
                            {{ number_format($spm->nilai_spm, 0, ',', '.') }}
                        </td>
                        <td class="p-5 text-sm text-center">
                            @if($spm->status == 'Draft')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Draft
                                </span>
                            @elseif($spm->status == 'Menunggu Verifikasi')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200 animate-pulse">
                                    Menunggu Verifikasi
                                </span>
                            @elseif($spm->status == 'Terverifikasi')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                    {{ $spm->status }}
                                </span>
                            @endif
                        </td>
                        <td class="p-5 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Detail -->
                                <button type="button" @click.prevent="openDetailModal('{{ route('spm.show', $spm->id) }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors tooltip" data-tip="Lihat Detail" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                
                                <!-- Edit (Hanya jika Draft, Menunggu Verifikasi, atau Ditolak) -->
                                @if(in_array($spm->status, ['Draft', 'Menunggu Verifikasi', 'Ditolak']))
                                    <a href="{{ route('spm.edit', $spm->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 transition-colors tooltip" data-tip="Edit SPM" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('spm.destroy', $spm->id) }}" method="POST" class="inline" 
                                        @submit.prevent="
                                            let form = $event.target;
                                            Swal.fire({
                                                title: 'Hapus SPM?',
                                                text: 'Tindakan ini tidak dapat dibatalkan dan semua lampiran akan terhapus.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ef4444', // red-500
                                                cancelButtonColor: '#64748b', // slate-500
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-3xl',
                                                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                                                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            })
                                        ">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors tooltip" data-tip="Hapus SPM" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-base font-medium">Belum ada data dokumen SPM.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($spms->hasPages())
    <div class="p-5 border-t border-slate-100 dark:border-slate-700 ajax-pagination flex justify-between items-center">
        <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
            Menampilkan <span class="font-bold text-slate-900 dark:text-white">{{ $spms->firstItem() ?? 0 }}</span> &mdash; <span class="font-bold text-slate-900 dark:text-white">{{ $spms->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900 dark:text-white">{{ $spms->total() }}</span>
        </div>
        <div class="flex items-center space-x-2">
            @if ($spms->onFirstPage())
                <span class="px-4 py-2 bg-slate-50 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700 rounded-full text-sm font-bold shadow-sm cursor-not-allowed">
                    &laquo; Prev
                </span>
            @else
                <button @click="fetchData('{{ $spms->previousPageUrl() }}')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-full text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    &laquo; Prev
                </button>
            @endif

            @if ($spms->hasMorePages())
                <button @click="fetchData('{{ $spms->nextPageUrl() }}')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-full text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    Next &raquo;
                </button>
            @else
                <span class="px-4 py-2 bg-slate-50 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700 rounded-full text-sm font-bold shadow-sm cursor-not-allowed">
                    Next &raquo;
                </span>
            @endif
        </div>
    </div>
    @endif
</div>
