<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($basts as $bast)
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative group overflow-hidden hover:shadow-md transition-shadow">
            
            <div class="flex items-center justify-between mb-4">
                <span class="px-3 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full 
                    {{ $bast->status == 'Terverifikasi' ? 'bg-emerald-100 text-emerald-700' : 
                       ($bast->status == 'Ditolak' ? 'bg-red-100 text-red-700' : 
                       'bg-amber-100 text-amber-700') }}">
                    {{ $bast->status }}
                </span>
                
                @if($bast->file_dokumen)
                <button @click="openDetailModal('{{ route('basts.show', $bast->id) }}')" class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:scale-110 transition-all tooltip" title="Lihat Dokumen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
                @endif
            </div>

            <div class="mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight">{{ $bast->nomor_bast }}</h3>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ \Carbon\Carbon::parse($bast->tanggal_bast)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl mb-4 border border-slate-100 dark:border-slate-800">
                <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nilai Penagihan</p>
                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">Rp {{ number_format($bast->nilai_penagihan, 0, ',', '.') }}</p>
                @if($bast->nomor_penagihan)
                    <p class="text-xs text-slate-500 mt-1 font-mono bg-white dark:bg-slate-800 px-2 py-1 rounded inline-block border border-slate-200 dark:border-slate-700">{{ $bast->nomor_penagihan }}</p>
                @endif
            </div>

            <div class="space-y-3 mb-2">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Paket Pekerjaan</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $bast->paketPekerjaan->nama_paket }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Penyedia Jasa</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $bast->paketPekerjaan->penyedia->nama_perusahaan }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Overlay -->
            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                @if(in_array(auth()->user()->role, ['admin', 'atasan']) || auth()->user()->id == $bast->uploaded_by)
                    <button @click="openEditModal({{ $bast }})" class="p-3 bg-white text-blue-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('basts.destroy', $bast->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen BAST ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-3 bg-white text-red-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                @endif
                
                <button @click="openDetailModal('{{ route('basts.show', $bast->id) }}')" class="p-3 bg-white text-emerald-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            
        </div>
    @empty
        <div class="col-span-full bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border border-slate-100 dark:border-slate-700">
            <div class="w-20 h-20 mx-auto bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300">Belum Ada Data BAST</h3>
            <p class="text-slate-500 mt-2">Data Berita Acara Serah Terima & Penagihan akan muncul di sini.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($basts->hasPages())
    <div class="mt-8 pagination">
        {{ $basts->links() }}
    </div>
@endif
