<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Dokumen SPM') }} - {{ $spm->nomor_spm }}
            </h2>
            <div class="flex space-x-2">
                <a href="#" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition shadow">
                    Cetak Tanda Terima
                </a>
                <a href="{{ route('spm.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm transition">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            <!-- Informasi Metadata -->
            <div class="w-full md:w-1/3 space-y-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Data Metadata</h3>
                    
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

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Riwayat Revisi (Audit)</h3>
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

            <!-- PDF Viewer with Alpine JS Tabs -->
            <div x-data="{ tab: 'spm' }" class="w-full md:w-2/3 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden flex flex-col">
                <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 overflow-x-auto">
                    @php 
                        $types = ['spm' => 'SPM', 'kuitansi' => 'Kuitansi', 'surat_tugas' => 'Surat Tugas', 'laporan' => 'BAST', 'dokumentasi' => 'Dokumentasi'];
                    @endphp
                    @foreach($types as $key => $label)
                        <button @click="tab = '{{ $key }}'" 
                                :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800': tab === '{{ $key }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== '{{ $key }}' }"
                                class="px-4 py-3 border-b-2 font-medium text-sm whitespace-nowrap transition-colors">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                
                <div class="flex-1 min-h-[600px] p-0 relative bg-gray-100 dark:bg-gray-900">
                    @foreach($types as $key => $label)
                        @php 
                            $attachment = $spm->attachments->where('tipe_file', $key)->first();
                        @endphp
                        <div x-show="tab === '{{ $key }}'" class="absolute inset-0 w-full h-full" style="display: none;">
                            @if($attachment)
                                @if(Str::endsWith($attachment->file_path, ['.jpg', '.jpeg', '.png']))
                                    <div class="w-full h-full flex items-center justify-center p-4">
                                        <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="{{ $label }}" class="max-w-full max-h-full object-contain shadow-lg">
                                    </div>
                                @else
                                    <iframe src="{{ asset('storage/' . $attachment->file_path) }}" class="w-full h-full border-0" title="{{ $label }}"></iframe>
                                @endif
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 space-y-3">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p>File {{ $label }} tidak dilampirkan.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
