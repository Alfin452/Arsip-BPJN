<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap">Nomor SP2D</th>
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap">Tanggal</th>
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap">Nilai (Rp)</th>
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap">SPM Induk</th>
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap text-center">Status</th>
                    <th class="p-5 font-bold text-slate-700 dark:text-slate-300 text-sm whitespace-nowrap text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @forelse($sp2ds as $sp2d)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/80 transition-colors group">
                        <td class="p-5">
                            <div class="font-bold text-slate-800 dark:text-white">{{ $sp2d->nomor_sp2d }}</div>
                        </td>
                        <td class="p-5 text-sm text-slate-600 dark:text-slate-400 font-medium">
                            {{ \Carbon\Carbon::parse($sp2d->tanggal_sp2d)->translatedFormat('d M Y') }}
                        </td>
                        <td class="p-5 text-sm">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1 rounded-lg border border-emerald-100 dark:border-emerald-800/50">
                                Rp {{ number_format($sp2d->nilai_sp2d, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="p-5 text-sm text-slate-600 dark:text-slate-400">
                            @if($sp2d->spm)
                                <span class="font-semibold">{{ $sp2d->spm->nomor_spm }}</span>
                                <div class="text-xs opacity-75 mt-1">{{ $sp2d->spm->satker ? $sp2d->spm->satker->nama_satker : '' }}</div>
                            @else
                                <span class="text-slate-400 italic">Tidak ada SPM</span>
                            @endif
                        </td>
                        <td class="p-5 text-center">
                            @if($sp2d->status == 'Draft')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Draft
                                </span>
                            @elseif($sp2d->status == 'Menunggu Verifikasi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu ACC
                                </span>
                            @elseif($sp2d->status == 'Terverifikasi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800/50 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="p-5 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Detail -->
                                <button type="button" @click.prevent="openDetailModal('{{ route('sp2d.show', $sp2d->id) }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors tooltip" data-tip="Lihat Detail" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                
                                <!-- Edit (Hanya jika Draft, Menunggu Verifikasi, atau Ditolak) -->
                                @if(in_array($sp2d->status, ['Draft', 'Menunggu Verifikasi', 'Ditolak']))
                                    <a href="{{ route('sp2d.edit', $sp2d->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 transition-colors tooltip" data-tip="Edit SP2D" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('sp2d.destroy', $sp2d->id) }}" method="POST" class="inline" 
                                        @submit.prevent="
                                            let form = $event.target;
                                            Swal.fire({
                                                title: 'Hapus SP2D?',
                                                text: 'Tindakan ini tidak dapat dibatalkan.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ef4444',
                                                cancelButtonColor: '#64748b',
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
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors tooltip" data-tip="Hapus SP2D" title="Hapus">
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
                                <p class="text-base font-medium">Belum ada data dokumen SP2D.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($sp2ds->hasPages())
    <div class="p-5 border-t border-slate-100 dark:border-slate-700 ajax-pagination flex justify-between items-center">
        <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
            Menampilkan <span class="font-bold text-slate-900 dark:text-white">{{ $sp2ds->firstItem() ?? 0 }}</span> &mdash; <span class="font-bold text-slate-900 dark:text-white">{{ $sp2ds->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-900 dark:text-white">{{ $sp2ds->total() }}</span>
        </div>
        <div class="flex items-center space-x-2">
            @if ($sp2ds->onFirstPage())
                <span class="px-4 py-2 bg-slate-50 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700 rounded-full text-sm font-bold shadow-sm cursor-not-allowed">
                    &laquo; Prev
                </span>
            @else
                <button @click="fetchData('{{ $sp2ds->previousPageUrl() }}')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-full text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    &laquo; Prev
                </button>
            @endif

            @if ($sp2ds->hasMorePages())
                <button @click="fetchData('{{ $sp2ds->nextPageUrl() }}')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-full text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
