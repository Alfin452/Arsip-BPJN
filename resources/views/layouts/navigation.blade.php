<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden print:hidden" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0 lg:ml-0' : '-translate-x-full lg:-ml-60'" class="fixed lg:relative inset-y-0 left-0 z-50 w-60 text-emerald-50 transition-all duration-300 ease-in-out flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.15)] border-r border-blue-800/30 overflow-hidden bg-gradient-to-br from-blue-950 via-blue-800 to-indigo-900 bg-[length:200%_200%] animate-gradient print:hidden">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-24 pt-4 pb-2 px-6 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('logo/Logo_Kementerian_Pekerjaan_Umum_Republik_Indonesia.svg') }}" alt="Logo PUPR" class="h-12 w-12 object-contain drop-shadow-md">
            <span class="text-xl font-bold tracking-tight text-white">Arsip BPJN</span>
        </a>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-blue-200 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Sidebar Links -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        
        <!-- MENU UTAMA -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }} mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        
        <!-- MODUL TRANSAKSI -->
        <a href="{{ route('dipas.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('dipas.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }} mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Pagu Anggaran
        </a>
        
        <a href="{{ route('paket-pekerjaans.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('paket-pekerjaans.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Paket Pekerjaan
        </a>

        <!-- MODUL DOKUMEN -->
        <a href="{{ route('basts.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('basts.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }} mt-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            BAST & Penagihan
        </a>

        <a href="{{ route('spm.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('spm.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Data SPM
        </a>
        
        <a href="{{ route('sp2d.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('sp2d.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
            Data SP2D
        </a>

        <div x-data="{ openLaporan: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="openLaporan = !openLaporan" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Analitik
                </div>
                <svg :class="openLaporan ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openLaporan" style="display: none;" class="pl-11 space-y-1 mt-1 pb-2">
                <a href="{{ route('laporan.realisasi-pagu') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.realisasi-pagu') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.realisasi-pagu') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Realisasi Pagu (Satker)
                </a>
                <a href="{{ route('laporan.waktu-proses') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.waktu-proses') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.waktu-proses') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Kinerja SLA Waktu
                </a>
                <a href="{{ route('laporan.tren-pencairan') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.tren-pencairan') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.tren-pencairan') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Tren Pencairan Bulanan
                </a>
                <a href="{{ route('laporan.serapan-paket') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.serapan-paket') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.serapan-paket') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Serapan Paket Pekerjaan
                </a>
                <a href="{{ route('laporan.distribusi-penyedia') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.distribusi-penyedia') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.distribusi-penyedia') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Distribusi Kontraktor
                </a>
                <a href="{{ route('laporan.status-dokumen') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.status-dokumen') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.status-dokumen') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Status Dokumen
                </a>
                <a href="{{ route('laporan.tagihan-outstanding') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.tagihan-outstanding') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.tagihan-outstanding') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Tagihan Outstanding
                </a>
                <a href="{{ route('laporan.kinerja-ppk') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.kinerja-ppk') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.kinerja-ppk') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Kinerja Penyerapan PPK
                </a>
                <a href="{{ route('laporan.sisa-waktu-kontrak') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.sisa-waktu-kontrak') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.sisa-waktu-kontrak') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Sisa Waktu Kontrak
                </a>
                <a href="{{ route('laporan.komposisi-jenis-spm') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('laporan.komposisi-jenis-spm') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('laporan.komposisi-jenis-spm') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Komposisi Jenis SPM
                </a>
            </div>
        </div>

        @if(auth()->user()->role == 'admin')
        
        <a href="{{ route('activity-log') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium text-blue-100/70 hover:bg-blue-800/50 hover:text-white mt-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Log Aktivitas
        </a>

        <!-- Dropdown Master Data -->
        <div x-data="{ open: {{ request()->routeIs('satker.*') || request()->routeIs('ppk.*') || request()->routeIs('penyedias.*') ? 'true' : 'false' }} }" class="space-y-1 mt-6">
            <button @click="open = !open" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('satker.*') || request()->routeIs('ppk.*') || request()->routeIs('penyedias.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Master Data
                </div>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" style="display: none;" class="pl-11 space-y-1 mt-1 pb-2">
                <a href="{{ route('satker.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('satker.*') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('satker.*') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Data Satker
                </a>
                <a href="{{ route('ppk.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('ppk.*') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('ppk.*') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Data PPK
                </a>
                <a href="{{ route('penyedias.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition font-medium {{ request()->routeIs('penyedias.*') ? 'text-white font-semibold' : 'text-blue-200/70 hover:text-white' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('penyedias.*') ? 'bg-blue-400' : 'bg-blue-200/50' }}"></div>
                    Penyedia Jasa
                </a>
            </div>
        </div>

        <!-- Manajemen Pengguna hanya untuk Admin -->
        <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition font-medium {{ request()->routeIs('users.*') ? 'bg-blue-800 text-white' : 'text-blue-100/70 hover:bg-blue-800/50 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Manajemen Pengguna
        </a>
    
        @endif
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="p-4 bg-blue-950/30 shrink-0 border-t border-blue-800/30">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center text-blue-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
