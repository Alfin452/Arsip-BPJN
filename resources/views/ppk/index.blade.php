<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Master Data Pejabat Pembuat Komitmen (PPK)') }}
        </h2>
    </x-slot>

    <div x-data="ppkApp()" class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <form method="GET" action="{{ route('ppk.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="block w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full text-sm placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors shadow-sm" placeholder="Cari Nama / NIP PPK...">
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if(auth()->user()->role === 'admin')
                    <button @click="openCreateModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah PPK
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
                @forelse($ppks as $ppk)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative group overflow-hidden">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="h-12 w-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight">{{ $ppk->nama }}</h3>
                                <p class="text-xs text-slate-500 font-mono mt-1">NIP: {{ $ppk->nip }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4 bg-slate-50 dark:bg-slate-700/30 p-3 rounded-xl border border-slate-100 dark:border-slate-700">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Jabatan</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $ppk->jabatan }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Satker Induk</p>
                                    <p class="text-sm font-bold text-blue-600 dark:text-blue-400 line-clamp-2">{{ $ppk->satker->nama_satker ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <button @click="openEditModal({{ $ppk }})" class="p-3 bg-white text-blue-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('ppk.destroy', $ppk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PPK ini? Data Paket Pekerjaan terkait mungkin ikut terhapus.')">
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
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada PPK</h3>
                        <p class="text-slate-500 mb-6">Silakan tambah data PPK terlebih dahulu.</p>
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
                     class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-visible shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 dark:border-slate-800">
                    
                    <form :action="formAction" method="POST">
                        @csrf
                        <template x-if="isEditMode">
                            @method('PUT')
                        </template>

                        <div class="px-6 py-6 sm:px-8 sm:py-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="isEditMode ? 'Edit PPK' : 'Tambah PPK Baru'"></h3>
                                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 bg-slate-100 dark:bg-slate-800 p-2 rounded-full transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="relative" x-data="{ openSatker: false }">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Satuan Kerja Induk <span class="text-red-500">*</span></label>
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
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama PPK <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama" x-model="formData.nama" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="Nama Pejabat...">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">NIP <span class="text-red-500">*</span></label>
                                        <input type="text" name="nip" x-model="formData.nip" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors font-mono" required placeholder="198001012005011001">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Jabatan</label>
                                    <input type="text" name="jabatan" x-model="formData.jabatan" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" placeholder="Pejabat Pembuat Komitmen (Default)">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-slate-100 dark:border-slate-800">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Simpan Data
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
        function ppkApp() {
            return {
                isModalOpen: false,
                isEditMode: false,
                formAction: '{{ route("ppk.store") }}',
                satkersList: @json($satkers),
                formData: {
                    id: null,
                    satker_id: '',
                    nip: '',
                    nama: '',
                    jabatan: 'Pejabat Pembuat Komitmen'
                },

                getSatkerName(id) {
                    return id ? this.satkersList.find(s => s.id == id)?.nama_satker : '';
                },

                openCreateModal() {
                    this.isEditMode = false;
                    this.formAction = '{{ route("ppk.store") }}';
                    this.formData = {
                        id: null,
                        satker_id: '',
                        nip: '',
                        nama: '',
                        jabatan: 'Pejabat Pembuat Komitmen'
                    };
                    this.isModalOpen = true;
                },
                openEditModal(data) {
                    this.isEditMode = true;
                    this.formAction = `/ppk/${data.id}`;
                    this.formData = {
                        id: data.id,
                        satker_id: data.satker_id,
                        nip: data.nip,
                        nama: data.nama,
                        jabatan: data.jabatan || 'Pejabat Pembuat Komitmen'
                    };
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                }
            }
        }
    </script>
</x-app-layout>
