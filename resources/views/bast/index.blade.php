<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Modul BAST & Penagihan') }}
        </h2>
    </x-slot>

    <div x-data="bastTable()" class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input x-model="filters.search" @input.debounce.500ms="applyFilters()" type="text" class="block w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full text-sm placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors shadow-sm" placeholder="Cari No BAST / Paket...">
                    </div>

                    <!-- Date Range Filter -->
                    <div class="relative flex items-center w-full md:w-auto bg-white dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm px-2">
                        <svg class="h-4 w-4 text-slate-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <input x-ref="startDate" type="text" class="w-24 md:w-28 text-sm border-0 focus:ring-0 bg-transparent dark:text-white text-center cursor-pointer" placeholder="Mulai">
                        <span class="text-slate-300 mx-1">-</span>
                        <input x-ref="endDate" type="text" class="w-24 md:w-28 text-sm border-0 focus:ring-0 bg-transparent dark:text-white text-center cursor-pointer" placeholder="Selesai">
                    </div>

                    <!-- Paket Pekerjaan Filter -->
                    <select x-model="filters.paket_pekerjaan_id" @change="applyFilters()" class="w-full md:w-48 text-sm border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full focus:ring-blue-500 focus:border-blue-500 dark:text-white shadow-sm">
                        <option value="">Semua Paket</option>
                        @foreach($paketPekerjaans as $paket)
                            <option value="{{ $paket->id }}">{{ Str::limit($paket->nama_paket, 30) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button @click="openCreateModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat BAST Baru
                    </button>
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

            <!-- Table Container (AJAX Content) -->
            <div id="bast-table-container" class="relative min-h-[400px]">
                <div x-show="isLoading" class="absolute inset-0 z-10 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center rounded-3xl">
                    <div class="flex flex-col items-center gap-3">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent shadow-md"></div>
                        <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">Memuat data...</span>
                    </div>
                </div>
                
                <div x-ref="tableContent">
                    @include('bast.partials.table', ['basts' => $basts])
                </div>
            </div>

            <!-- Create/Edit Modal -->
            <div x-show="isCreateModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" @click="closeCreateModal()" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="isCreateModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-100 dark:border-slate-700">
                        
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white" x-text="isEdit ? 'Edit BAST & Penagihan' : 'Buat BAST & Penagihan Baru'"></h3>
                            <button type="button" @click="closeCreateModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <form :action="formAction" method="POST" enctype="multipart/form-data">
                            @csrf
                            <template x-if="isEdit">
                                <input type="hidden" name="_method" value="PUT">
                            </template>
                            
                            <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Paket Pekerjaan <span class="text-red-500">*</span></label>
                                        
                                        <div class="relative" x-data="{
                                            open: false,
                                            options: [
                                                @foreach($paketPekerjaans as $paket)
                                                { value: '{{ $paket->id }}', label: '{{ addslashes($paket->nama_paket) }} ({{ addslashes($paket->penyedia->nama_perusahaan) }})' },
                                                @endforeach
                                            ],
                                            selectedLabel() {
                                                return this.options.find(o => o.value == formData.paket_pekerjaan_id)?.label || 'Pilih Paket Pekerjaan...';
                                            }
                                        }">
                                            <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center justify-between w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-left text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <span class="block truncate" :class="!formData.paket_pekerjaan_id ? 'text-slate-400' : ''" x-text="selectedLabel()"></span>
                                                <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                            <input type="hidden" name="paket_pekerjaan_id" x-model="formData.paket_pekerjaan_id" required>
                                            
                                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden" style="display: none;">
                                                <ul class="max-h-60 overflow-auto py-2">
                                                    <template x-for="option in options" :key="option.value">
                                                        <li @click="formData.paket_pekerjaan_id = option.value; open = false;" 
                                                            :class="{'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold': formData.paket_pekerjaan_id == option.value, 'hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300': formData.paket_pekerjaan_id != option.value}"
                                                            class="px-4 py-3 text-sm cursor-pointer border-b border-slate-50 dark:border-slate-700/50 last:border-0 transition-colors" x-text="option.label"></li>
                                                    </template>
                                                    <li x-show="options.length === 0" class="px-4 py-3 text-sm text-slate-500 text-center">Tidak ada data</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor BAST <span class="text-red-500">*</span></label>
                                        <input type="text" name="nomor_bast" x-model="formData.nomor_bast" required class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal BAST <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d', onChange: function(selectedDates, dateStr) { formData.tanggal_bast = dateStr; } })" x-model="formData.tanggal_bast" name="tanggal_bast" required class="w-full pl-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors cursor-pointer" placeholder="Pilih Tanggal">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Penagihan / Invoice</label>
                                        <input type="text" name="nomor_penagihan" x-model="formData.nomor_penagihan" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Penagihan</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="text" x-init="flatpickr($el, { dateFormat: 'Y-m-d', onChange: function(selectedDates, dateStr) { formData.tanggal_penagihan = dateStr; } })" x-model="formData.tanggal_penagihan" name="tanggal_penagihan" class="w-full pl-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors cursor-pointer" placeholder="Pilih Tanggal">
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nilai Penagihan (Rp) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-500 font-medium">Rp</span>
                                            </div>
                                            <input type="text" x-model="formattedNilaiPenagihan" @input="formatRupiahInput" required class="w-full pl-10 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors font-mono">
                                            <input type="hidden" name="nilai_penagihan" x-model="formData.nilai_penagihan">
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Upload Dokumen BAST & Kuitansi (PDF)</label>
                                        <input type="file" name="file_dokumen" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-slate-700 dark:file:text-white transition-colors">
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Maks. 10MB. Format PDF.</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan Tambahan</label>
                                        <textarea name="keterangan" x-model="formData.keterangan" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 rounded-b-3xl">
                                <button type="button" @click="closeCreateModal()" class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">Batal</button>
                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:scale-105">Simpan BAST</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Detail Modal (AJAX) -->
            <div x-show="isDetailModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="isDetailModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="closeDetailModal()" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="isDetailModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full max-w-5xl border border-slate-200 dark:border-slate-700">
                        <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                            <button type="button" @click="closeDetailModal()" class="bg-white/80 dark:bg-slate-800/80 backdrop-blur text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white rounded-full p-2 focus:outline-none transition-colors shadow-sm">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div id="detail-modal-content" class="w-full h-full">
                            <!-- Injected via AJAX -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('bastTable', () => ({
                isLoading: false,
                isCreateModalOpen: false,
                isDetailModalOpen: false,
                isEdit: false,
                formAction: '{{ route('basts.store') }}',
                formattedNilaiPenagihan: '',
                formData: {
                    id: null,
                    paket_pekerjaan_id: '',
                    nomor_bast: '',
                    tanggal_bast: '',
                    nomor_penagihan: '',
                    tanggal_penagihan: '',
                    nilai_penagihan: '',
                    keterangan: ''
                },
                filters: {
                    search: '',
                    paket_pekerjaan_id: '',
                    start_date: '',
                    end_date: ''
                },
                
                init() {
                    let fpStart = flatpickr(this.$refs.startDate, {
                        dateFormat: "Y-m-d",
                        onChange: (selectedDates, dateStr) => {
                            this.filters.start_date = dateStr;
                            if (this.filters.end_date) this.applyFilters();
                        }
                    });
                    let fpEnd = flatpickr(this.$refs.endDate, {
                        dateFormat: "Y-m-d",
                        onChange: (selectedDates, dateStr) => {
                            this.filters.end_date = dateStr;
                            if (this.filters.start_date) this.applyFilters();
                        }
                    });
                },

                formatRupiahInput(e) {
                    let value = e.target.value.replace(/[^,\d]/g, '');
                    let parts = value.split(',');
                    let sisa = parts[0].length % 3;
                    let rupiah = parts[0].substr(0, sisa);
                    let ribuan = parts[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = parts[1] != undefined ? rupiah + ',' + parts[1] : rupiah;
                    this.formattedNilaiPenagihan = rupiah;
                    
                    // Set actual value for hidden input
                    this.formData.nilai_penagihan = value;
                },
                
                fetchData(url = '{{ route("basts.index") }}') {
                    this.isLoading = true;
                    
                    const params = new URLSearchParams();
                    if (this.filters.search) params.append('search', this.filters.search);
                    if (this.filters.paket_pekerjaan_id) params.append('paket_pekerjaan_id', this.filters.paket_pekerjaan_id);
                    if (this.filters.start_date) params.append('start_date', this.filters.start_date);
                    if (this.filters.end_date) params.append('end_date', this.filters.end_date);

                    const finalUrl = url.includes('?') ? `${url}&${params.toString()}` : `${url}?${params.toString()}`;

                    fetch(finalUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        this.$refs.tableContent.innerHTML = html;
                        // Re-bind pagination links
                        this.$refs.tableContent.querySelectorAll('.pagination a').forEach(link => {
                            link.addEventListener('click', (e) => {
                                e.preventDefault();
                                this.fetchData(link.href);
                            });
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error))
                    .finally(() => {
                        this.isLoading = false;
                    });
                },
                
                applyFilters() {
                    this.fetchData();
                },

                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = '{{ route('basts.store') }}';
                    this.formData = {
                        id: null,
                        paket_pekerjaan_id: '',
                        nomor_bast: '',
                        tanggal_bast: '',
                        nomor_penagihan: '',
                        tanggal_penagihan: '',
                        nilai_penagihan: '',
                        keterangan: ''
                    };
                    this.formattedNilaiPenagihan = '';
                    this.isCreateModalOpen = true;
                },

                openEditModal(bast) {
                    this.isEdit = true;
                    this.formAction = `/basts/${bast.id}`;
                    this.formData = { ...bast };
                    // Format rupiah for edit
                    let val = (bast.nilai_penagihan || 0).toString().replace('.00', '');
                    this.formData.nilai_penagihan = val;
                    
                    let sisa = val.length % 3;
                    let rupiah = val.substr(0, sisa);
                    let ribuan = val.substr(sisa).match(/\d{3}/gi);
                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }
                    this.formattedNilaiPenagihan = rupiah;
                    
                    // Format date correctly
                    if (bast.tanggal_bast) this.formData.tanggal_bast = bast.tanggal_bast.substring(0,10);
                    if (bast.tanggal_penagihan) this.formData.tanggal_penagihan = bast.tanggal_penagihan.substring(0,10);
                    
                    this.isCreateModalOpen = true;
                },

                closeCreateModal() {
                    this.isCreateModalOpen = false;
                },
                
                openDetailModal(url) {
                    document.getElementById('detail-modal-content').innerHTML = `
                        <div class="p-12 flex flex-col items-center justify-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent mb-4"></div>
                            <p class="text-slate-500 font-medium">Memuat detail dokumen...</p>
                        </div>
                    `;
                    this.isDetailModalOpen = true;
                    
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('detail-modal-content').innerHTML = html;
                    })
                    .catch(error => {
                        document.getElementById('detail-modal-content').innerHTML = `
                            <div class="p-12 text-center">
                                <div class="text-red-500 mb-4"><svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                <h3 class="text-xl font-bold text-slate-800">Gagal memuat data</h3>
                                <p class="text-slate-500 mt-2">Terjadi kesalahan pada sistem.</p>
                            </div>
                        `;
                    });
                },
                
                closeDetailModal() {
                    this.isDetailModalOpen = false;
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
