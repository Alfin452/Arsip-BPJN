<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section (e-apotek style) -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-0 print:hidden">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">@yield('title')</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">@yield('description')</p>
                </div>
                <div>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak PDF
                    </a>
                </div>
            </div>

            <!-- Print Header -->
            <div class="hidden print:block mb-8 text-center border-b-2 border-slate-800 pb-4">
                <h1 class="text-2xl font-bold text-slate-900">KEMENTERIAN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT</h1>
                <h2 class="text-xl font-bold text-slate-800">BALAI PELAKSANAAN JALAN NASIONAL JAWA TIMUR</h2>
                <p class="text-sm text-slate-600 mt-2 font-medium">@yield('title') - Diekspor pada: {{ now()->format('d M Y H:i') }}</p>
            </div>

            <!-- Filter Section (e-apotek style) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-6 print:hidden">
                <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-4 items-end">
                    @yield('filters')
                    
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-5 py-2.5 bg-slate-800 dark:bg-slate-700 text-white rounded-xl font-medium hover:bg-slate-700 dark:hover:bg-slate-600 transition-colors shadow-sm text-sm">
                            Terapkan Filter
                        </button>
                        <a href="{{ url()->current() }}" class="px-5 py-2.5 text-slate-500 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-colors text-sm">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Chart Section (e-apotek style) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-100 dark:border-slate-700 shadow-sm mb-6">
                @yield('chart')
            </div>

            <!-- Table Section (e-apotek style) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="overflow-x-auto">
                    @yield('table')
                </div>
            </div>
            
        </div>
    </div>

    @push('scripts')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @yield('page-scripts')
    @endpush
    
    <style>
        @media print {
            body { background: white !important; }
            .print\:block { display: block !important; }
            .print\:hidden { display: none !important; }
            /* Strip box styles for print */
            .bg-white { background: transparent !important; }
            .rounded-3xl { border-radius: 0 !important; }
            .shadow-sm { box-shadow: none !important; }
            .border { border: none !important; }
            .border-slate-100 { border-color: transparent !important; }
        }
    </style>
</x-app-layout>
