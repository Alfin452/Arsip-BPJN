<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arsip BPJN') }}</title>

        <!-- Fonts -->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
            /* Sidebar Animation CSS */
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-gradient {
                animation: gradient 8s ease infinite;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Flatpickr CSS & JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

        <!-- Global SweetAlert Confirmations -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Konfirmasi Hapus
                document.body.addEventListener('submit', function (e) {
                    if (e.target.classList.contains('form-delete')) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-6 py-2.5',
                                cancelButton: 'rounded-xl px-6 py-2.5'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                e.target.submit();
                            }
                        });
                    }
                    
                    // Konfirmasi Simpan
                    if (e.target.classList.contains('form-save')) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Simpan Data?',
                            text: "Pastikan semua data sudah terisi dengan benar.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Simpan!',
                            cancelButtonText: 'Periksa Lagi',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-6 py-2.5',
                                cancelButton: 'rounded-xl px-6 py-2.5'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                e.target.submit();
                            }
                        });
                    }
                });

                // Konfirmasi Logout
                document.body.addEventListener('click', function (e) {
                    const logoutBtn = e.target.closest('.btn-logout');
                    if (logoutBtn) {
                        e.preventDefault();
                        const form = logoutBtn.closest('form');
                        Swal.fire({
                            title: 'Keluar Aplikasi?',
                            text: "Sesi Anda akan diakhiri.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Keluar',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl px-6 py-2.5',
                                cancelButton: 'rounded-xl px-6 py-2.5'
                            }
                        }).then((result) => {
                            if (result.isConfirmed && form) {
                                form.submit();
                            }
                        });
                    }
                });
            });
        </script>
    </head>
    <body class="antialiased text-slate-900 bg-[#F4F7FE] dark:bg-slate-900 dark:text-slate-100 transition-colors duration-200">
        <div class="flex h-screen overflow-hidden bg-[#F4F7FE] dark:bg-slate-900 transition-colors duration-200" 
             x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
             @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">
            
            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Main Content Wrapper -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                <!-- Top Header -->
                <header class="sticky top-0 z-30 flex items-center justify-between px-6 lg:px-8 h-24 pt-4 pb-2 bg-[#F4F7FE]/90 dark:bg-slate-900/90 backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        <!-- Menu Toggle -->
                        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-blue-600 focus:outline-none bg-white dark:bg-slate-800 p-2 rounded-full shadow-sm transition-colors">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <!-- Page Title in Header -->
                        @isset($header)
                            <div class="hidden sm:block dark:text-white">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <!-- Right Actions Pill -->
                    <div class="flex items-center bg-white dark:bg-slate-800 rounded-full p-1.5 shadow-sm border border-slate-100 dark:border-slate-700 gap-2 pr-2">
                        
                        <!-- Notification Bell -->
                        <button onclick="Swal.fire({title: 'Coming Soon!', text: 'Fitur notifikasi sedang dalam pengembangan.', icon: 'info', confirmButtonColor: '#3b82f6', customClass: {popup: 'rounded-2xl', confirmButton: 'rounded-xl px-6 py-2.5'}})" class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-full transition-colors relative ml-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span class="absolute top-1 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
                        </button>

                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 text-slate-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-full transition-colors mr-2 focus:outline-none">
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>

                        <!-- Profile Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center focus:outline-none transition ease-in-out duration-150 p-1.5 rounded-full text-slate-400 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')" class="text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ __('Profile') }}
                                    </div>
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block w-full px-4 py-2 text-sm leading-5 text-red-600 hover:text-red-700 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none focus:bg-slate-100 transition duration-150 ease-in-out btn-logout">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            {{ __('Log Out') }}
                                        </div>
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 mt-2">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- SweetAlert Global Script -->
        @if (session('success'))
            <script>
                Swal.fire({
                    title: '{{ session('success') }}',
                    iconHtml: '<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top',
                    background: '#0f172a', /* slate-900 */
                    color: '#f8fafc', /* slate-50 */
                    customClass: {
                        popup: '!rounded-full !px-6 !py-3 !mt-6 shadow-2xl border border-slate-800',
                        title: '!text-sm !font-medium !m-0 !p-0 !leading-6',
                        icon: '!border-0 !m-0 !mr-3 !w-auto !h-auto flex items-center justify-center'
                    }
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    title: '{{ session('error') }}',
                    iconHtml: '<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    showConfirmButton: false,
                    timer: 4000,
                    toast: true,
                    position: 'top',
                    background: '#0f172a', /* slate-900 */
                    color: '#f8fafc', /* slate-50 */
                    customClass: {
                        popup: '!rounded-full !px-6 !py-3 !mt-6 shadow-2xl border border-slate-800',
                        title: '!text-sm !font-medium !m-0 !p-0 !leading-6',
                        icon: '!border-0 !m-0 !mr-3 !w-auto !h-auto flex items-center justify-center'
                    }
                });
            </script>
        @endif
    </body>
</html>
