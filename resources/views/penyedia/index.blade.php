<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Master Data Penyedia Jasa') }}
        </h2>
    </x-slot>

    <div x-data="penyediaApp()" class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Controls & Filter Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <form method="GET" action="{{ route('penyedias.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="block w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-full text-sm placeholder-slate-400 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-colors shadow-sm" placeholder="Cari Nama / NPWP...">
                    </div>
                </form>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    @if(auth()->user()->role === 'admin')
                    <button @click="openCreateModal()" class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Penyedia Jasa
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
                @forelse($penyedias as $penyedia)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative group overflow-hidden">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-12 w-12 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg line-clamp-2 leading-tight">{{ $penyedia->nama_perusahaan }}</h3>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">NPWP</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $penyedia->npwp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Direktur / Penanggung Jawab</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $penyedia->nama_direktur ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Rekening Bank</p>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                        {{ $penyedia->bank ?? '-' }}<br>
                                        <span class="font-mono text-xs">{{ $penyedia->no_rekening ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <!-- Actions Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <button @click="openEditModal({{ $penyedia }})" class="p-3 bg-white text-blue-600 rounded-full shadow-lg hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('penyedias.destroy', $penyedia->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Penyedia Jasa ini? Hati-hati: Data Paket Pekerjaan yang menggunakan penyedia ini mungkin akan terpengaruh.')">
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
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada Penyedia Jasa</h3>
                        <p class="text-slate-500 mb-6">Silakan tambah data rekanan / penyedia jasa terlebih dahulu.</p>
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
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="isEditMode ? 'Edit Penyedia Jasa' : 'Tambah Penyedia Baru'"></h3>
                                <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 bg-slate-100 dark:bg-slate-800 p-2 rounded-full transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Perusahaan / PT <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_perusahaan" x-model="formData.nama_perusahaan" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" required placeholder="PT. Maju Mundur">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">NPWP</label>
                                        <input type="text" name="npwp" x-model="formData.npwp" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Direktur / PJ</label>
                                        <input type="text" name="nama_direktur" x-model="formData.nama_direktur" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Bank</label>
                                        <input type="text" name="bank" x-model="formData.bank" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors" placeholder="Mandiri / BRI">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">No. Rekening</label>
                                        <input type="text" name="no_rekening" x-model="formData.no_rekening" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                                    </div>
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
        function penyediaApp() {
            return {
                isModalOpen: false,
                isEditMode: false,
                formAction: '{{ route("penyedias.store") }}',
                formData: {
                    id: null,
                    nama_perusahaan: '',
                    npwp: '',
                    nama_direktur: '',
                    bank: '',
                    no_rekening: ''
                },
                openCreateModal() {
                    this.isEditMode = false;
                    this.formAction = '{{ route("penyedias.store") }}';
                    this.formData = {
                        id: null,
                        nama_perusahaan: '',
                        npwp: '',
                        nama_direktur: '',
                        bank: '',
                        no_rekening: ''
                    };
                    this.isModalOpen = true;
                },
                openEditModal(data) {
                    this.isEditMode = true;
                    this.formAction = `/penyedias/${data.id}`;
                    this.formData = {
                        id: data.id,
                        nama_perusahaan: data.nama_perusahaan,
                        npwp: data.npwp,
                        nama_direktur: data.nama_direktur,
                        bank: data.bank,
                        no_rekening: data.no_rekening
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
