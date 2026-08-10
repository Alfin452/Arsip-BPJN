<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Dokumen SP2D') }} - {{ $sp2d->nomor_sp2d }}
            </h2>
            <a href="{{ route('sp2d.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm transition">
                &larr; Kembali
            </a>
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
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Nomor SP2D</dt>
                            <dd>{{ $sp2d->nomor_sp2d }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Tanggal</dt>
                            <dd>{{ \Carbon\Carbon::parse($sp2d->tanggal_sp2d)->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Nilai (Rp)</dt>
                            <dd class="text-green-600 dark:text-green-400 font-bold">Rp {{ number_format($sp2d->nilai_sp2d, 2, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Keterangan</dt>
                            <dd>{{ $sp2d->keterangan ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Kaitan SPM</dt>
                            <dd>
                                @if($sp2d->spm)
                                    <a href="{{ route('spm.show', $sp2d->spm->id) }}" class="text-indigo-600 hover:underline">{{ $sp2d->spm->nomor_spm }}</a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Status</dt>
                            <dd>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $sp2d->status == 'verified' ? 'bg-green-100 text-green-800' : ($sp2d->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($sp2d->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2">Informasi Audit</h3>
                    <dl class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Diunggah Oleh</dt>
                            <dd>{{ $sp2d->uploader->name }} ({{ $sp2d->created_at->diffForHumans() }})</dd>
                        </div>
                        @if($sp2d->verified_by)
                        <div>
                            <dt class="font-semibold text-gray-800 dark:text-gray-200">Diverifikasi Oleh</dt>
                            <dd>{{ $sp2d->verifier->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- PDF Viewer -->
            <div class="w-full md:w-2/3 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden flex flex-col">
                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="font-medium text-gray-900 dark:text-gray-100">Pratinjau Dokumen</h3>
                    <a href="{{ asset('storage/' . $sp2d->file_pdf) }}" target="_blank" class="text-green-600 hover:text-green-900 dark:text-green-400 text-sm font-semibold">Buka Penuh &rarr;</a>
                </div>
                <div class="flex-1 min-h-[600px] p-0">
                    <iframe src="{{ asset('storage/' . $sp2d->file_pdf) }}" class="w-full h-full border-0" title="PDF Viewer"></iframe>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
