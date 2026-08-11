<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight tracking-tight">
                    {{ __('Unggah Dokumen SPM') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lengkapi metadata dan lampirkan dokumen pendukung SPM baru.</p>
            </div>
            <a href="{{ route('spm.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl transition-all font-semibold shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('spm.store') }}" method="POST" enctype="multipart/form-data" class="form-save relative">
                    @csrf
                    
                    <div class="p-8 sm:p-10 space-y-10">
                        
                        <!-- Bagian Metadata -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Metadata SPM</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <!-- Nomor SPM -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor SPM <span class="text-red-500">*</span></label>
                                    <input type="text" name="nomor_spm" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" placeholder="Contoh: 000123P/2026" required>
                                </div>
                                
                                <!-- Tanggal SPM (Flatpickr) -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal SPM <span class="text-red-500">*</span></label>
                                    <input x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" type="text" name="tanggal_spm" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm cursor-pointer" placeholder="Pilih Tanggal" required>
                                </div>

                                <!-- Nilai SPM (Rupiah Format) -->
                                <div x-data="{ 
                                    rawValue: '',
                                    formattedValue: '',
                                    formatRupiah() {
                                        // Hapus semua karakter selain angka
                                        let num = this.formattedValue.replace(/[^0-9]/g, '');
                                        this.rawValue = num;
                                        if(num) {
                                            this.formattedValue = new Intl.NumberFormat('id-ID').format(num);
                                        } else {
                                            this.formattedValue = '';
                                        }
                                    }
                                }">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nilai SPM (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Rp</span>
                                        </div>
                                        <input type="text" x-model="formattedValue" @input="formatRupiah" class="block w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm font-mono" placeholder="0" required>
                                        <!-- Hidden input to store raw number for form submission -->
                                        <input type="hidden" name="nilai_spm" x-model="rawValue">
                                    </div>
                                </div>

                                <!-- Tahun Anggaran (Custom Select Year) -->
                                <div class="z-40">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tahun Anggaran <span class="text-red-500">*</span></label>
                                    @php
                                        $currentYear = date('Y');
                                        $yearOptions = [];
                                        for($i = $currentYear + 1; $i >= 2020; $i--) {
                                            $yearOptions[] = ['value' => (string)$i, 'label' => (string)$i];
                                        }
                                    @endphp
                                    <x-custom-select 
                                        name="tahun_anggaran"
                                        placeholder="Pilih Tahun" 
                                        :options="$yearOptions"
                                        value="{{ date('Y') }}"
                                    />
                                </div>
                                
                                <!-- Jenis SPM (Custom Select) -->
                                <div class="z-30">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jenis SPM <span class="text-red-500">*</span></label>
                                    <x-custom-select 
                                        name="jenis_spm"
                                        placeholder="Pilih Jenis SPM" 
                                        :options="[
                                            ['value' => 'UP', 'label' => 'UP (Uang Persediaan)'],
                                            ['value' => 'TUP', 'label' => 'TUP (Tambahan Uang Persediaan)'],
                                            ['value' => 'GUP', 'label' => 'GUP (Penggantian Uang Persediaan)'],
                                            ['value' => 'PTUP', 'label' => 'PTUP (Pertanggungjawaban TUP)'],
                                            ['value' => 'LS', 'label' => 'LS (Langsung)']
                                        ]" 
                                    />
                                </div>

                                <!-- Satker (Custom Select Searchable) -->
                                <div class="z-20">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Satuan Kerja (Satker) <span class="text-red-500">*</span></label>
                                    @php
                                        $satkerOptions = $satkers->map(function($s) {
                                            return ['value' => $s->id, 'label' => $s->kode_satker . ' — ' . $s->nama_satker];
                                        })->toArray();
                                    @endphp
                                    <x-custom-select 
                                        name="satker_id"
                                        placeholder="Cari Satker..." 
                                        :options="$satkerOptions"
                                        :searchable="true"
                                    />
                                </div>

                                <!-- PPK (Custom Select Searchable) -->
                                <div class="z-10 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pejabat Pembuat Komitmen (PPK) <span class="text-red-500">*</span></label>
                                    @php
                                        $ppkOptions = $ppks->map(function($p) {
                                            return ['value' => $p->id, 'label' => $p->nip . ' — ' . $p->nama];
                                        })->toArray();
                                    @endphp
                                    <x-custom-select 
                                        name="ppk_id"
                                        placeholder="Cari PPK..." 
                                        :options="$ppkOptions"
                                        :searchable="true"
                                    />
                                </div>
                            </div>
                            
                            <!-- Uraian & Keterangan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mt-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Uraian Pembayaran</label>
                                    <textarea name="uraian" rows="3" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm resize-none" placeholder="Tuliskan uraian ringkas pembayaran..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan Tambahan</label>
                                    <textarea name="keterangan" rows="3" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm resize-none" placeholder="(Opsional) Catatan khusus..."></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100 dark:border-slate-700">

                        <!-- Bagian Upload File -->
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Lampiran Dokumen</h3>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 pl-13">Unggah file dokumen yang relevan. Format harus PDF (maks. 10MB per file).</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- 1. Dokumen SPM Utama -->
                                <div class="bg-blue-50/50 dark:bg-blue-900/10 p-5 rounded-2xl border-2 border-blue-100 dark:border-blue-800/50 relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                    <div class="absolute -right-6 -top-6 text-blue-100 dark:text-blue-900/20 transform rotate-12 group-hover:scale-110 transition-transform">
                                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <label class="block text-sm font-bold text-blue-900 dark:text-blue-300 mb-3 relative z-10">1. Dokumen SPM Induk <span class="text-red-500">*</span></label>
                                    <input type="file" name="file_spm" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors file:cursor-pointer cursor-pointer relative z-10" required>
                                </div>
                                
                                <!-- 2. Kuitansi -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 group hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">2. Kuitansi / Bukti Bayar</label>
                                    <input type="file" name="file_kuitansi" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200 hover:file:bg-slate-300 dark:hover:file:bg-slate-600 file:transition-colors file:cursor-pointer cursor-pointer">
                                </div>

                                <!-- 3. Surat Tugas -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 group hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">3. Surat Tugas / SPD</label>
                                    <input type="file" name="file_surat_tugas" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200 hover:file:bg-slate-300 dark:hover:file:bg-slate-600 file:transition-colors file:cursor-pointer cursor-pointer">
                                </div>

                                <!-- 4. BAST -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 group hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">4. Laporan (BAST / BTO)</label>
                                    <input type="file" name="file_laporan" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200 hover:file:bg-slate-300 dark:hover:file:bg-slate-600 file:transition-colors file:cursor-pointer cursor-pointer">
                                </div>
                                
                                <!-- 5. Dokumentasi -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 md:col-span-2 group hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">5. Dokumentasi Foto Lapangan (Opsional)</label>
                                    <input type="file" name="file_dokumentasi" accept="application/pdf,image/jpeg,image/png" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 dark:file:bg-slate-700 dark:file:text-slate-200 hover:file:bg-slate-300 dark:hover:file:bg-slate-600 file:transition-colors file:cursor-pointer cursor-pointer">
                                    <p class="text-xs text-slate-400 mt-2">Dapat berupa PDF atau gabungan gambar (JPG/PNG).</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                        
                        <!-- Toggle Draft -->
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_draft" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Simpan sebagai Draft</span>
                            </label>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('spm.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center focus:ring-2 focus:ring-slate-200">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan / Ajukan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
