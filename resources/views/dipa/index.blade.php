<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Pagu Anggaran (DIPA)') }}
        </h2>
    </x-slot>
    
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <div x-data="dipaApp()" class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <!-- Filters -->
                <form method="GET" action="{{ route('dipas.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto" id="filterForm">
                    <!-- Filter Tahun -->
                    <select name="tahun" onchange="document.getElementById('filterForm').submit()" class="py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                        @if($tahuns->isEmpty())
                            <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @endif
                        @foreach($tahuns as $thn)
                            <option value="{{ $thn }}" {{ request('tahun', date('Y')) == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
                        @endforeach
                    </select>

                    @if(auth()->user()->role === 'admin')
                    <!-- Filter Satker -->
                    <select name="satker_id" onchange="document.getElementById('filterForm').submit()" class="py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                        <option value="">Semua Satker</option>
                        @foreach($satkers as $satker)
                            <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                        @endforeach
                    </select>
                    @endif
                </form>

                <!-- Actions -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if(auth()->user()->role === 'admin')
                    <button @click="openCreateModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat DIPA Baru
                    </button>
                    @endif
                </div>
            </div>

            <!-- Error/Success Messages -->
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

            <!-- Grid DIPA -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($dipas as $dipa)
                    @php
                        $percentage = $dipa->nilai_pagu > 0 ? ($dipa->realisasi / $dipa->nilai_pagu) * 100 : 0;
                        $colorClass = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-emerald-500');
                    @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative group overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg">{{ $dipa->satker->nama_satker }}</h3>
                                <p class="text-sm text-slate-500 font-mono">{{ $dipa->nomor_dipa }}</p>
                            </div>
                            <div class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300">
                                TA {{ $dipa->tahun_anggaran }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-semibold">Total Pagu (DIPA)</p>
                            <p class="text-2xl font-black text-slate-800 dark:text-white">Rp {{ number_format($dipa->nilai_pagu, 0, ',', '.') }}</p>
                        </div>

                        <!-- Progress Bar Serapan -->
                        <div class="mb-3">
                            <div class="flex justify-between items-end mb-2">
                                <p class="text-xs font-bold text-slate-500">Serapan Anggaran</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ number_format($percentage, 1, ',', '.') }}%</p>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 mb-2 overflow-hidden">
                                <div class="h-3 rounded-full {{ $colorClass }} transition-all duration-1000 relative" style="width: {{ min($percentage, 100) }}%">
                                    <!-- Shimmer effect -->
                                    <div class="absolute inset-0 bg-white/20 w-full animate-shimmer" style="background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent); transform: skewX(-20deg);"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Realisasi</p>
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($dipa->realisasi, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Sisa Pagu</p>
                                <p class="text-sm font-bold {{ $dipa->sisa_pagu < 0 ? 'text-red-500' : 'text-emerald-600 dark:text-emerald-400' }}">Rp {{ number_format($dipa->sisa_pagu, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <button @click="openEditModal({{ $dipa }})" class="p-3 bg-white text-blue-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('dipas.destroy', $dipa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus DIPA ini? Sisa pagu dan perhitungan realisasi akan terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-white text-red-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border border-slate-100 dark:border-slate-700">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada DIPA</h3>
                        <p class="text-slate-500 mb-6">Silakan buat dokumen Pagu Anggaran (DIPA) terlebih dahulu.</p>
                        @if(auth()->user()->role === 'admin')
                        <button @click="openCreateModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-bold transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat DIPA Baru
                        </button>
                        @endif
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
                
                <!-- Background overlay -->
                <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="closeModal()"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal panel -->
                <div x-show="isModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-visible shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 dark:border-slate-800">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <template x-if="isEditMode">
                            @method('PUT')
                        </template>

                        <div class="px-6 py-6 sm:px-8 sm:py-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="isEditMode ? 'Edit DIPA' : 'Buat DIPA Baru'"></h3>
                                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 bg-slate-100 dark:bg-slate-800 p-2 rounded-full transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                <template x-if="!isEditMode">
                                    <div class="relative" x-data="{ openSatker: false }">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Satuan Kerja (Satker) <span class="text-red-500">*</span></label>
                                        <!-- Hidden input to hold the actual value -->
                                        <input type="hidden" name="satker_id" x-model="formData.satker_id" required>
                                        
                                        <!-- Custom Dropdown Button -->
                                        <button type="button" @click="openSatker = !openSatker" @click.away="openSatker = false" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center p-3 transition-colors">
                                            <span x-text="getSatkerName(formData.satker_id) || '-- Pilih Satker --'" :class="formData.satker_id ? 'text-slate-900 dark:text-white' : 'text-slate-500'"></span>
                                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="{'rotate-180': openSatker}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div x-show="openSatker" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden max-h-60 overflow-y-auto">
                                            <ul class="py-2">
                                                @foreach($satkers as $satker)
                                                    <li @click="formData.satker_id = '{{ $satker->id }}'; openSatker = false;" class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-slate-700/50 cursor-pointer text-sm text-slate-700 dark:text-slate-200 transition-colors">
                                                        {{ $satker->nama_satker }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="!isEditMode">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tahun Anggaran <span class="text-red-500">*</span></label>
                                        <input type="number" name="tahun_anggaran" x-model="formData.tahun_anggaran" min="2000" max="2100" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="Contoh: 2024">
                                    </div>
                                </template>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor DIPA <span class="text-red-500">*</span></label>
                                    <input type="text" name="nomor_dipa" x-model="formData.nomor_dipa" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="SP DIPA-XXX.XX.X.XXXXX/2024">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Pengesahan DIPA <span class="text-red-500">*</span></label>
                                        <input type="text" x-ref="datepicker" name="tanggal_dipa" x-model="formData.tanggal_dipa" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="Pilih Tanggal...">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Total Nilai Pagu (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 font-bold">Rp</span>
                                        </div>
                                        <!-- Hidden Input for actual value -->
                                        <input type="hidden" name="nilai_pagu" :value="rawNilaiPagu">
                                        
                                        <!-- Visible Formatted Input -->
                                        <input type="text" x-model="formattedNilaiPagu" @input="updateRawPagu($event.target.value)" class="w-full pl-10 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors font-mono text-lg font-bold" required placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-slate-100 dark:border-slate-800">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Simpan DIPA
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
        function dipaApp() {
            return {
                isModalOpen: false,
                isEditMode: false,
                formAction: '{{ route("dipas.store") }}',
                formData: {
                    id: null,
                    satker_id: '',
                    tahun_anggaran: '{{ date("Y") }}',
                    nomor_dipa: '',
                    tanggal_dipa: '',
                    nilai_pagu: ''
                },
                rawNilaiPagu: '',
                formattedNilaiPagu: '',
                flatpickrInstance: null,
                satkersList: @json($satkers),

                init() {
                    this.$watch('isModalOpen', (value) => {
                        if (value) {
                            setTimeout(() => {
                                if (this.flatpickrInstance) this.flatpickrInstance.destroy();
                                this.flatpickrInstance = flatpickr(this.$refs.datepicker, {
                                    locale: "id",
                                    dateFormat: "Y-m-d",
                                    defaultDate: this.formData.tanggal_dipa || null
                                });
                            }, 50);
                        }
                    });
                },

                getSatkerName(id) {
                    if (!id) return '';
                    const satker = this.satkersList.find(s => s.id == id);
                    return satker ? satker.nama_satker : '';
                },

                formatRupiah(value) {
                    if (!value) return '';
                    let val = value.toString().replace(/[^0-9]/g, '');
                    return val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                updateRawPagu(value) {
                    let raw = value.replace(/[^0-9]/g, '');
                    this.rawNilaiPagu = raw;
                    this.formattedNilaiPagu = this.formatRupiah(raw);
                },

                openCreateModal() {
                    this.isEditMode = false;
                    this.formAction = '{{ route("dipas.store") }}';
                    this.formData = {
                        id: null,
                        satker_id: '',
                        tahun_anggaran: '{{ date("Y") }}',
                        nomor_dipa: '',
                        tanggal_dipa: '',
                        nilai_pagu: ''
                    };
                    this.rawNilaiPagu = '';
                    this.formattedNilaiPagu = '';
                    this.isModalOpen = true;
                },
                openEditModal(dipa) {
                    this.isEditMode = true;
                    this.formAction = `/dipas/${dipa.id}`;
                    this.formData = {
                        id: dipa.id,
                        satker_id: dipa.satker_id,
                        tahun_anggaran: dipa.tahun_anggaran,
                        nomor_dipa: dipa.nomor_dipa,
                        tanggal_dipa: dipa.tanggal_dipa,
                        nilai_pagu: dipa.nilai_pagu
                    };
                    // Ensure it parses correctly without decimals if any
                    let numericVal = parseInt(dipa.nilai_pagu);
                    this.rawNilaiPagu = numericVal.toString();
                    this.formattedNilaiPagu = this.formatRupiah(numericVal);
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            }
        }
    </script>
    
    <style>
        /* Shimmer Animation */
        @keyframes shimmer {
            0% { transform: translateX(-100%) skewX(-20deg); }
            100% { transform: translateX(200%) skewX(-20deg); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>
</x-app-layout>
