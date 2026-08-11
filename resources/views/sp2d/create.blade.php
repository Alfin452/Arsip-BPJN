<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight tracking-tight">
                    {{ __('Input Dokumen SP2D') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lengkapi informasi dan lampirkan dokumen SP2D resmi dari KPPN.</p>
            </div>
            <a href="{{ route('sp2d.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl transition-all font-semibold shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10" x-data="sp2dForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
                <form action="{{ route('sp2d.store') }}" method="POST" enctype="multipart/form-data" class="form-save relative">
                    @csrf
                    
                    @if ($errors->any())
                    <div class="p-8 pb-0">
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 rounded-2xl p-5 flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h4 class="text-sm font-bold text-red-800 dark:text-red-400">Terdapat kesalahan pada isian Anda:</h4>
                                <ul class="mt-2 text-sm text-red-600 dark:text-red-300 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="p-8 sm:p-10 space-y-10">
                        
                        <!-- Bagian Informasi Utama -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Informasi Utama</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                
                                <!-- Pilih SPM -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">SPM Induk <span class="text-red-500">*</span></label>
                                    <select name="spm_id" id="spm_id" x-model="selectedSpm" @change="updateSpmInfo()" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" required>
                                        <option value="">-- Pilih Nomor SPM (Terverifikasi) --</option>
                                        @foreach($spms as $spm)
                                            <option value="{{ $spm->id }}" 
                                                    data-nilai="{{ $spm->nilai_spm }}"
                                                    data-satker="{{ $spm->satker ? $spm->satker->nama_satker : '-' }}"
                                                    data-ppk="{{ $spm->ppk ? $spm->ppk->nama : '-' }}"
                                                    @if(old('spm_id', $selectedSpmId) == $spm->id) selected @endif>
                                                {{ $spm->nomor_spm }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Hanya menampilkan SPM yang sudah Terverifikasi dan belum memiliki SP2D.</p>
                                </div>

                                <!-- Info SPM Auto-fill (Readonly) -->
                                <div x-show="spmInfo.satker" x-transition class="md:col-span-2 bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl p-5 flex flex-col sm:flex-row gap-6" style="display: none;">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Satuan Kerja</p>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200" x-text="spmInfo.satker"></p>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">PPK</p>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-200" x-text="spmInfo.ppk"></p>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Nilai SPM (Max)</p>
                                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="formatRupiahView(spmInfo.nilai)"></p>
                                    </div>
                                </div>

                                <!-- Nomor SP2D -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor SP2D <span class="text-red-500">*</span></label>
                                    <input type="text" name="nomor_sp2d" value="{{ old('nomor_sp2d') }}" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm font-medium uppercase" placeholder="Contoh: 123456N/001/012" required>
                                </div>
                                
                                <!-- Tanggal SP2D -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal SP2D Terbit <span class="text-red-500">*</span></label>
                                    <input x-init="flatpickr($el, { dateFormat: 'Y-m-d' })" type="text" name="tanggal_sp2d" value="{{ old('tanggal_sp2d') }}" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm cursor-pointer" placeholder="Pilih Tanggal" required>
                                </div>

                                <!-- Nilai SP2D -->
                                <div x-data="{ 
                                    rawValue: '{{ old('nilai_sp2d') }}',
                                    formattedValue: '{{ old('nilai_sp2d') ? number_format(old('nilai_sp2d'), 0, '', '') : '' }}',
                                    init() {
                                        if (this.formattedValue) {
                                            this.formatRupiah();
                                        }
                                        // Update from auto-fill
                                        this.$watch('nilaiSp2d', (value) => {
                                            if (value) {
                                                this.rawValue = value.toString();
                                                this.formattedValue = new Intl.NumberFormat('id-ID').format(value);
                                            } else {
                                                this.rawValue = '';
                                                this.formattedValue = '';
                                            }
                                        });
                                    },
                                    formatRupiah() {
                                        let num = String(this.formattedValue).replace(/[^0-9]/g, '');
                                        this.rawValue = num;
                                        if(num) {
                                            this.formattedValue = new Intl.NumberFormat('id-ID').format(num);
                                            this.nilaiSp2d = num;
                                        } else {
                                            this.formattedValue = '';
                                            this.nilaiSp2d = '';
                                        }
                                    }
                                }" class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nilai SP2D (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium">Rp</span>
                                        </div>
                                        <input type="text" x-model="formattedValue" @input="formatRupiah" class="block w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm font-mono text-lg font-bold" placeholder="0" required>
                                        <!-- Hidden input for raw number -->
                                        <input type="hidden" name="nilai_sp2d" x-model="rawValue">
                                    </div>
                                    <p class="mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400" x-show="nilaiSp2d > 0" x-text="terbilang(nilaiSp2d) + ' rupiah'"></p>
                                </div>
                                
                                <!-- Keterangan -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Keterangan / Uraian SP2D <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                    <textarea name="keterangan" rows="3" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm" placeholder="Contoh: Pembayaran Termin II Paket Pekerjaan X">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Lampiran Dokumen -->
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Lampiran Dokumen Resmi</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Unggah file SP2D dalam format PDF (maks. 10MB).</p>
                                </div>
                            </div>
                            
                            <!-- Dokumen SP2D Utama -->
                            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-5 rounded-2xl border-2 border-blue-100 dark:border-blue-800/50 relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                                <div class="absolute -right-6 -top-6 text-blue-100 dark:text-blue-900/20 transform rotate-12 group-hover:scale-110 transition-transform">
                                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                </div>
                                <label class="block text-sm font-bold text-blue-900 dark:text-blue-300 mb-3 relative z-10">Dokumen SP2D PDF <span class="text-red-500">*</span></label>
                                <input type="file" name="file_pdf" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors file:cursor-pointer cursor-pointer relative z-10" required>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center">
                            <!-- Placeholder to keep button right aligned if no left elements -->
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('sp2d.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center focus:ring-2 focus:ring-slate-200">
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

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sp2dForm', () => ({
                selectedSpm: '{{ old("spm_id", $selectedSpmId) }}',
                nilaiSp2d: '{{ old("nilai_sp2d") }}',
                spmInfo: {
                    satker: '',
                    ppk: '',
                    nilai: 0
                },
                
                init() {
                    if (this.selectedSpm) {
                        setTimeout(() => this.updateSpmInfo(), 100);
                    }
                },
                
                updateSpmInfo() {
                    if (!this.selectedSpm) {
                        this.spmInfo = { satker: '', ppk: '', nilai: 0 };
                        return;
                    }
                    
                    const select = document.getElementById('spm_id');
                    const option = select.options[select.selectedIndex];
                    
                    this.spmInfo = {
                        satker: option.dataset.satker,
                        ppk: option.dataset.ppk,
                        nilai: parseFloat(option.dataset.nilai)
                    };
                    
                    // Auto-fill nominal SP2D if it's empty
                    if (!this.nilaiSp2d) {
                        this.nilaiSp2d = this.spmInfo.nilai;
                    }
                },
                
                formatRupiahView(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },
                
                terbilang(angka) {
                    if(!angka) return '';
                    angka = parseInt(angka);
                    if(isNaN(angka)) return '';
                    
                    var bilangan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
                    var str = "";
                    if(angka < 12) {
                        str = " " + bilangan[angka];
                    } else if(angka < 20) {
                        str = this.terbilang(angka - 10) + " belas";
                    } else if(angka < 100) {
                        str = this.terbilang(Math.floor(angka/10)) + " puluh" + this.terbilang(angka % 10);
                    } else if(angka < 200) {
                        str = " seratus" + this.terbilang(angka - 100);
                    } else if(angka < 1000) {
                        str = this.terbilang(Math.floor(angka/100)) + " ratus" + this.terbilang(angka % 100);
                    } else if(angka < 2000) {
                        str = " seribu" + this.terbilang(angka - 1000);
                    } else if(angka < 1000000) {
                        str = this.terbilang(Math.floor(angka/1000)) + " ribu" + this.terbilang(angka % 1000);
                    } else if(angka < 1000000000) {
                        str = this.terbilang(Math.floor(angka/1000000)) + " juta" + this.terbilang(angka % 1000000);
                    } else if(angka < 1000000000000) {
                        str = this.terbilang(Math.floor(angka/1000000000)) + " milyar" + this.terbilang(angka % 1000000000);
                    } else {
                        str = "Lebih dari satu triliun";
                    }
                    return str;
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
