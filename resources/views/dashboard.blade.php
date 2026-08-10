<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Selamat Datang, ") }} {{ Auth::user()->name }}! (Role: {{ ucfirst(Auth::user()->role) }})
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Card 1 -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Total SPM</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $total_spm }}</div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Total SP2D</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">{{ $total_sp2d }}</div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Nilai SPM (Rp)</div>
                        <div class="mt-1 text-2xl font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($nilai_spm, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Nilai SP2D (Rp)</div>
                        <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">{{ number_format($nilai_sp2d, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
