<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Paket Pekerjaan & Kontrak') }}
        </h2>
    </x-slot>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <div x-data="paketApp()" class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <form method="GET" action="{{ route('paket-pekerjaans.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto" id="filterForm">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="block w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full text-sm placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors shadow-sm" placeholder="Cari Nama Paket / Kontrak...">
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if(auth()->user()->role === 'admin')
                    <button @click="openCreateModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Paket Pekerjaan
                    </button>
                    @endif
                </div>
            </div>

            <!-- Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pakets as $paket)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative group overflow-hidden flex flex-col h-full">
                        
                        <div class="mb-4 flex-1">
                            <div class="flex justify-between items-start gap-4 mb-2">
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight">{{ $paket->nama_paket }}</h3>
                            </div>
                            <p class="text-xs font-mono text-slate-500 bg-slate-100 dark:bg-slate-700 inline-block px-2 py-1 rounded-md">{{ $paket->nomor_kontrak }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold mb-1">Nilai Kontrak</p>
                            <p class="text-xl font-black text-slate-800 dark:text-white">Rp {{ number_format($paket->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>

                        <div class="space-y-2 mb-4 bg-slate-50 dark:bg-slate-700/30 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                            <div class="flex items-start justify-between text-sm">
                                <span class="text-slate-500 font-medium text-xs">Penyedia Jasa:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-right">{{ $paket->penyedia->nama_perusahaan }}</span>
                            </div>
                            <div class="flex items-start justify-between text-sm">
                                <span class="text-slate-500 font-medium text-xs">Satker:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-right">{{ $paket->satker->nama_satker }}</span>
                            </div>
                            <div class="flex items-start justify-between text-sm">
                                <span class="text-slate-500 font-medium text-xs">PPK:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200 text-right">{{ $paket->ppk->nama }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 dark:border-slate-700 pt-4">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Mulai</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $paket->tanggal_mulai ? date('d M Y', strtotime($paket->tanggal_mulai)) : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Selesai</p>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $paket->tanggal_selesai ? date('d M Y', strtotime($paket->tanggal_selesai)) : '-' }}</p>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <button @click="openEditModal({{ $paket }})" class="p-3 bg-white text-blue-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            @if(auth()->user()->role != 'atasan')
<form action="{{ route('paket-pekerjaans.destroy', $paket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Paket Pekerjaan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-white text-red-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
@endif
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border border-slate-100 dark:border-slate-700">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada Paket Pekerjaan</h3>
                        <p class="text-slate-500 mb-6">Silakan buat data kontrak proyek terlebih dahulu.</p>
                    </div>
                @endforelse
            </div>
            
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="isModalOpen" 
             style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="closeModal()"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="isModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-visible shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-100 dark:border-slate-800">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <template x-if="isEditMode">
                            @method('PUT')
                        </template>

                        <div class="px-6 py-6 sm:px-8 sm:py-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="isEditMode ? 'Edit Paket Pekerjaan' : 'Tambah Paket Pekerjaan'"></h3>
                                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 bg-slate-100 dark:bg-slate-800 p-2 rounded-full transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative" x-data="{ openSatker: false }">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Satuan Kerja <span class="text-red-500">*</span></label>
                                        <input type="hidden" name="satker_id" x-model="formData.satker_id" required>
                                        <button type="button" @click="openSatker = !openSatker" @click.away="openSatker = false" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center p-3 transition-colors">
                                            <span class="truncate" x-text="getSatkerName(formData.satker_id) || '-- Pilih Satker --'" :class="formData.satker_id ? 'text-slate-900 dark:text-white' : 'text-slate-500'"></span>
                                            <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': openSatker}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="openSatker" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto">
                                            <ul class="py-2">
                                                @foreach($satkers as $satker)
                                                    <li @click="formData.satker_id = '{{ $satker->id }}'; openSatker = false;" class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer text-sm text-slate-700 dark:text-slate-200 transition-colors">
                                                        {{ $satker->nama_satker }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="relative" x-data="{ openPpk: false }">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">PPK <span class="text-red-500">*</span></label>
                                        <input type="hidden" name="ppk_id" x-model="formData.ppk_id" required>
                                        <button type="button" @click="openPpk = !openPpk" @click.away="openPpk = false" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center p-3 transition-colors">
                                            <span class="truncate" x-text="getPpkName(formData.ppk_id) || '-- Pilih PPK --'" :class="formData.ppk_id ? 'text-slate-900 dark:text-white' : 'text-slate-500'"></span>
                                            <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': openPpk}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="openPpk" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto">
                                            <ul class="py-2">
                                                @foreach($ppks as $ppk)
                                                    <li @click="formData.ppk_id = '{{ $ppk->id }}'; openPpk = false;" class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer text-sm text-slate-700 dark:text-slate-200 transition-colors">
                                                        {{ $ppk->nama }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative" x-data="{ openPenyedia: false }">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Penyedia Jasa (Kontraktor) <span class="text-red-500">*</span></label>
                                    <input type="hidden" name="penyedia_id" x-model="formData.penyedia_id" required>
                                    <button type="button" @click="openPenyedia = !openPenyedia" @click.away="openPenyedia = false" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center p-3 transition-colors">
                                        <span class="truncate" x-text="getPenyediaName(formData.penyedia_id) || '-- Pilih Penyedia --'" :class="formData.penyedia_id ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-500'"></span>
                                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': openPenyedia}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="openPenyedia" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden max-h-48 overflow-y-auto">
                                        <ul class="py-2">
                                            @foreach($penyedias as $penyedia)
                                                <li @click="formData.penyedia_id = '{{ $penyedia->id }}'; openPenyedia = false;" class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer text-sm text-slate-700 dark:text-slate-200 transition-colors">
                                                    {{ $penyedia->nama_perusahaan }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Paket Pekerjaan <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_paket" x-model="formData.nama_paket" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="Contoh: Preservasi Jalan Ruas A">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor Kontrak <span class="text-red-500">*</span></label>
                                        <input type="text" name="nomor_kontrak" x-model="formData.nomor_kontrak" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors font-mono" required placeholder="HK.02.01/XXX/2024">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Kontrak <span class="text-red-500">*</span></label>
                                        <input type="text" x-ref="tglKontrak" name="tanggal_kontrak" x-model="formData.tanggal_kontrak" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="Pilih Tanggal...">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Total Nilai Kontrak (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 font-bold">Rp</span>
                                        </div>
                                        <input type="hidden" name="nilai_kontrak" :value="rawNilaiKontrak">
                                        <input type="text" x-model="formattedNilaiKontrak" @input="updateRawKontrak($event.target.value)" class="w-full pl-10 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors font-mono text-lg font-bold" required placeholder="0">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Mulai (Opsional)</label>
                                        <input type="text" x-ref="tglMulai" name="tanggal_mulai" x-model="formData.tanggal_mulai" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" placeholder="Pilih Tanggal...">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Selesai (Opsional)</label>
                                        <input type="text" x-ref="tglSelesai" name="tanggal_selesai" x-model="formData.tanggal_selesai" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" placeholder="Pilih Tanggal...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-slate-100 dark:border-slate-800">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Simpan Paket
                            </button>
                            <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-6 py-3 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function paketApp() {
            return {
                isModalOpen: false,
                isEditMode: false,
                formAction: '{{ route("paket-pekerjaans.store") }}',
                formData: {
                    id: null,
                    satker_id: '',
                    ppk_id: '',
                    penyedia_id: '',
                    nama_paket: '',
                    nomor_kontrak: '',
                    tanggal_kontrak: '',
                    nilai_kontrak: '',
                    tanggal_mulai: '',
                    tanggal_selesai: ''
                },
                rawNilaiKontrak: '',
                formattedNilaiKontrak: '',
                satkersList: @json($satkers),
                ppksList: @json($ppks),
                penyediasList: @json($penyedias),
                fpKontrak: null,
                fpMulai: null,
                fpSelesai: null,

                init() {
                    this.$watch('isModalOpen', (value) => {
                        if (value) {
                            setTimeout(() => {
                                if (this.fpKontrak) this.fpKontrak.destroy();
                                if (this.fpMulai) this.fpMulai.destroy();
                                if (this.fpSelesai) this.fpSelesai.destroy();

                                const fpConfig = { locale: "id", dateFormat: "Y-m-d" };
                                this.fpKontrak = flatpickr(this.$refs.tglKontrak, { ...fpConfig, defaultDate: this.formData.tanggal_kontrak || null });
                                this.fpMulai = flatpickr(this.$refs.tglMulai, { ...fpConfig, defaultDate: this.formData.tanggal_mulai || null });
                                this.fpSelesai = flatpickr(this.$refs.tglSelesai, { ...fpConfig, defaultDate: this.formData.tanggal_selesai || null });
                            }, 50);
                        }
                    });
                },

                getSatkerName(id) {
                    return id ? this.satkersList.find(s => s.id == id)?.nama_satker : '';
                },
                getPpkName(id) {
                    return id ? this.ppksList.find(s => s.id == id)?.nama : '';
                },
                getPenyediaName(id) {
                    return id ? this.penyediasList.find(s => s.id == id)?.nama_perusahaan : '';
                },

                formatRupiah(value) {
                    if (!value) return '';
                    let val = value.toString().replace(/[^0-9]/g, '');
                    return val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                updateRawKontrak(value) {
                    let raw = value.replace(/[^0-9]/g, '');
                    this.rawNilaiKontrak = raw;
                    this.formattedNilaiKontrak = this.formatRupiah(raw);
                },

                openCreateModal() {
                    this.isEditMode = false;
                    this.formAction = '{{ route("paket-pekerjaans.store") }}';
                    this.formData = {
                        id: null,
                        satker_id: '',
                        ppk_id: '',
                        penyedia_id: '',
                        nama_paket: '',
                        nomor_kontrak: '',
                        tanggal_kontrak: '',
                        nilai_kontrak: '',
                        tanggal_mulai: '',
                        tanggal_selesai: ''
                    };
                    this.rawNilaiKontrak = '';
                    this.formattedNilaiKontrak = '';
                    this.isModalOpen = true;
                },
                openEditModal(data) {
                    this.isEditMode = true;
                    this.formAction = `/paket-pekerjaans/${data.id}`;
                    this.formData = {
                        ...data
                    };
                    let numericVal = parseInt(data.nilai_kontrak);
                    this.rawNilaiKontrak = numericVal.toString();
                    this.formattedNilaiKontrak = this.formatRupiah(numericVal);
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            }
        }
    </script>
</x-app-layout>
