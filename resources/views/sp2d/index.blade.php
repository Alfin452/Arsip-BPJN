<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Daftar Dokumen SP2D') }}
        </h2>
    </x-slot>

    <div class="py-4" x-data="sp2dTable()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats/Dashboard Mini -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total SP2D -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div class="h-12 w-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Total SP2D</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>

                <!-- Menunggu ACC -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div class="h-12 w-12 rounded-full bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Menunggu ACC</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $stats['menunggu'] }}</p>
                    </div>
                </div>

                <!-- Terverifikasi -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div class="h-12 w-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Terverifikasi</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $stats['terverifikasi'] }}</p>
                    </div>
                </div>

                <!-- Total Dana Cair -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div class="h-12 w-12 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">Total Dana Cair</p>
                        <p class="text-lg font-black text-slate-800 dark:text-white truncate" title="Rp {{ number_format($stats['total_cair'], 0, ',', '.') }}">
                            Rp {{ number_format($stats['total_cair'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Header Controls -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <!-- Filter & Search -->
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <!-- Search -->
                    <div class="relative w-full md:w-48">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input x-model.debounce.500ms="filters.search" @input="fetchData()" type="text" placeholder="Cari SP2D atau SPM..." class="block w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full text-sm placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors shadow-sm">
                    </div>

                    <!-- Filter Status -->
                    <div class="relative w-full md:w-40" :class="open ? 'z-50' : 'z-40'" x-data="{
                        open: false,
                        options: [
                            { value: '', label: 'Semua Status' },
                            { value: 'Draft', label: 'Draft' },
                            { value: 'Menunggu Verifikasi', label: 'Menunggu Verifikasi' },
                            { value: 'Terverifikasi', label: 'Terverifikasi' },
                            { value: 'Ditolak', label: 'Ditolak' }
                        ],
                        selectedLabel(val) {
                            return this.options.find(o => o.value === val)?.label || 'Semua Status';
                        }
                    }">
                        <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center justify-between w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                            <span class="truncate pr-2" x-text="selectedLabel(filters.status)"></span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                            <ul class="max-h-60 overflow-auto py-2">
                                <template x-for="option in options" :key="option.value">
                                    <li @click="filters.status = option.value; open = false; fetchData()" 
                                        :class="{'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold': filters.status === option.value, 'hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300': filters.status !== option.value}"
                                        class="px-4 py-2 text-sm cursor-pointer transition-colors" x-text="option.label"></li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Filter Satker (Hanya untuk Admin & Atasan) -->
                    @if(in_array(auth()->user()->role, ['admin', 'atasan']))
                    <div class="relative w-full md:w-40" :class="open ? 'z-50' : 'z-40'" x-data="{
                        open: false,
                        options: [
                            { value: '', label: 'Semua Satker' },
                            @foreach($satkers as $satker)
                            { value: '{{ $satker->id }}', label: '{{ addslashes($satker->nama_satker) }}' },
                            @endforeach
                        ],
                        selectedLabel(val) {
                            return this.options.find(o => o.value == val)?.label || 'Semua Satker';
                        }
                    }">
                        <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center justify-between w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                            <span class="truncate pr-2" x-text="selectedLabel(filters.satker_id)"></span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full md:w-72 mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                            <ul class="max-h-60 overflow-auto py-2">
                                <template x-for="option in options" :key="option.value">
                                    <li @click="filters.satker_id = option.value; open = false; fetchData()" 
                                        :class="{'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold': filters.satker_id == option.value, 'hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300': filters.satker_id != option.value}"
                                        class="px-4 py-2 text-sm cursor-pointer transition-colors" x-text="option.label"></li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Filter PPK (Hanya untuk Admin) -->
                    <div class="relative w-full md:w-40" :class="open ? 'z-50' : 'z-40'" x-data="{
                        open: false,
                        options: [
                            { value: '', label: 'Semua PPK' },
                            @foreach($ppks as $ppk)
                            { value: '{{ $ppk->id }}', label: '{{ addslashes($ppk->nama) }}' },
                            @endforeach
                        ],
                        selectedLabel(val) {
                            return this.options.find(o => o.value == val)?.label || 'Semua PPK';
                        }
                    }">
                        <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center justify-between w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                            <span class="truncate pr-2" x-text="selectedLabel(filters.ppk_id)"></span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full md:w-72 mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                            <ul class="max-h-60 overflow-auto py-2">
                                <template x-for="option in options" :key="option.value">
                                    <li @click="filters.ppk_id = option.value; open = false; fetchData()" 
                                        :class="{'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold': filters.ppk_id == option.value, 'hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300': filters.ppk_id != option.value}"
                                        class="px-4 py-2 text-sm cursor-pointer transition-colors" x-text="option.label"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- Filter Tanggal (Start & End) -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <input x-model="filters.start_date" x-init="flatpickr($el, { dateFormat: 'Y-m-d', onChange: function(selectedDates, dateStr) { filters.start_date = dateStr; if(filters.end_date) fetchData(); } })" type="text" placeholder="Tanggal Awal" class="block w-full md:w-36 py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all" title="Tanggal Awal">
                        <span class="text-slate-400">-</span>
                        <input x-model="filters.end_date" x-init="flatpickr($el, { dateFormat: 'Y-m-d', onChange: function(selectedDates, dateStr) { filters.end_date = dateStr; if(filters.start_date) fetchData(); } })" type="text" placeholder="Tanggal Akhir" class="block w-full md:w-36 py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-all" title="Tanggal Akhir">
                        
                        <!-- Reset Filter -->
                        <button @click="resetFilters()" type="button" class="ml-1 p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-full transition-colors" title="Reset Semua Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button @click="exportCsv()" type="button" class="w-full md:w-auto bg-emerald-100 hover:bg-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:hover:bg-emerald-800/50 dark:text-emerald-400 font-medium py-2 px-5 rounded-full transition-all duration-200 active:scale-95 shadow-sm flex items-center justify-center gap-2 border border-emerald-200 dark:border-emerald-800/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Export CSV</span>
                    </button>
                    @if(auth()->user()->role != 'atasan')
<a href="{{ route('sp2d.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-5 rounded-full transition-all duration-200 active:scale-95 shadow-sm flex items-center justify-center gap-2 group">
                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Input SP2D</span>
                    </a>
@endif
                </div>
            </div>

            <!-- Main Content Area (Table) -->
            <div x-ref="tableContainer" class="relative">
                @include('sp2d.partials.table')
            </div>

            <!-- Global Loading Overlay -->
            <div x-show="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm" style="display: none;">
                <div class="flex flex-col items-center gap-3 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl">
                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300">Memuat Data...</p>
                </div>
            </div>

            <!-- Modal Detail SP2D -->
            <div x-show="showModal" 
                 class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-sm p-4 md:p-0"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                <div class="relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]"
                     @click.away="showModal = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Detail Dokumen SP2D
                        </h3>
                        <button @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Modal Body (Dynamic) -->
                    <div class="flex-1 overflow-y-auto" x-html="modalContent">
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <!-- Flatpickr for Date Inputs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sp2dTable', () => ({
                isLoading: false,
                filters: {
                    search: '',
                    status: '',
                    satker_id: '',
                    ppk_id: '',
                    start_date: '',
                    end_date: ''
                },
                
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.has('show')) {
                        const sp2dId = urlParams.get('show');
                        this.openDetailModal(`/sp2d/${sp2dId}`);
                        
                        // Clear the URL parameter so it doesn't open again on refresh
                        const newUrl = window.location.pathname;
                        window.history.replaceState({}, document.title, newUrl);
                    }
                },
                
                fetchData(url = '{{ route("sp2d.index") }}') {
                    this.isLoading = true;
                    
                    const params = new URLSearchParams();
                    if(this.filters.search) params.append('search', this.filters.search);
                    if(this.filters.status) params.append('status', this.filters.status);
                    if(this.filters.satker_id) params.append('satker_id', this.filters.satker_id);
                    if(this.filters.ppk_id) params.append('ppk_id', this.filters.ppk_id);
                    if(this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if(this.filters.end_date) params.append('end_date', this.filters.end_date);
                    
                    const finalUrl = url.includes('?') 
                        ? `${url}&${params.toString()}` 
                        : `${url}?${params.toString()}`;

                    fetch(finalUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        this.$refs.tableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching data:', error))
                    .finally(() => {
                        this.isLoading = false;
                    });
                },

                resetFilters() {
                    this.filters = {
                        search: '',
                        status: '',
                        satker_id: '',
                        ppk_id: '',
                        start_date: '',
                        end_date: ''
                    };
                    this.fetchData();
                },

                exportCsv() {
                    const params = new URLSearchParams();
                    if(this.filters.search) params.append('search', this.filters.search);
                    if(this.filters.status) params.append('status', this.filters.status);
                    if(this.filters.satker_id) params.append('satker_id', this.filters.satker_id);
                    if(this.filters.ppk_id) params.append('ppk_id', this.filters.ppk_id);
                    if(this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if(this.filters.end_date) params.append('end_date', this.filters.end_date);
                    
                    window.location.href = `{{ route("sp2d.export") }}?${params.toString()}`;
                },

                showModal: false,
                isModalLoading: false,
                modalContent: '',

                async openDetailModal(url) {
                    this.showModal = true;
                    this.isModalLoading = true;
                    this.modalContent = '<div class="flex items-center justify-center p-12"><div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div></div>';

                    try {
                        const separator = url.includes('?') ? '&' : '?';
                        const response = await fetch(`${url}${separator}t=${new Date().getTime()}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html',
                                'Cache-Control': 'no-cache'
                            }
                        });
                        
                        if (response.ok) {
                            this.modalContent = await response.text();
                        } else {
                            throw new Error('Gagal memuat detail dokumen.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.modalContent = '<div class="p-8 text-center text-red-500 font-bold">Gagal memuat detail SP2D.</div>';
                    } finally {
                        this.isModalLoading = false;
                    }
                }
            }))
        })

        // Function untuk verifikasi SP2D
        function updateSp2dStatus(id, status) {
            let title = status === 'Terverifikasi' ? 'Konfirmasi Verifikasi' : 'Tolak SP2D';
            let text = status === 'Terverifikasi' ? 'Apakah Anda yakin ingin memverifikasi dokumen SP2D ini? Status SPM Induk akan otomatis menjadi Tercairkan.' : 'Apakah Anda yakin ingin menolak dokumen SP2D ini?';
            
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: status === 'Terverifikasi' ? '#059669' : '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5',
                    cancelButton: 'rounded-xl px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/sp2d/${id}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Status SP2D berhasil diperbarui.',
                                icon: 'success',
                                confirmButtonColor: '#3b82f6',
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error();
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
