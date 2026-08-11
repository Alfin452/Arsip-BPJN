<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white leading-tight">
                    @yield('title')
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">@yield('description')</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all shadow-sm print:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
                <button onclick="window.print()" style="background-color: #2563eb;" class="inline-flex items-center gap-2 px-4 py-2 border border-transparent rounded-xl font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all shadow-md print:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filter Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 print:hidden">
                <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-4 items-end">
                    @yield('filters')
                    
                    <div>
                        <button type="submit" class="px-5 py-2.5 bg-slate-800 dark:bg-slate-700 text-white rounded-xl font-medium hover:bg-slate-700 dark:hover:bg-slate-600 transition-colors shadow-sm">
                            Terapkan Filter
                        </button>
                        <a href="{{ url()->current() }}" class="px-5 py-2.5 text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-colors ml-2">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Report Content (This will be captured by html2pdf) -->
            <div id="report-content" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                
                <!-- Report Header for PDF only -->
                <div class="hidden print:block mb-8 text-center border-b-2 border-slate-800 pb-4" id="print-header">
                    <h1 class="text-2xl font-bold text-slate-900">KEMENTERIAN PEKERJAAN UMUM DAN PERUMAHAN RAKYAT</h1>
                    <h2 class="text-xl font-bold text-slate-800">BALAI PELAKSANAAN JALAN NASIONAL JAWA TIMUR</h2>
                    <p class="text-sm text-slate-600 mt-2">@yield('title') - Diekspor pada: {{ now()->format('d M Y H:i') }}</p>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 print:text-black">Grafik Laporan</h3>
                    <div class="w-full bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50 print:bg-white print:border-none">
                        @yield('chart')
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 print:text-black">Tabel Data</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700 print:border-slate-300">
                        @yield('table')
                    </div>
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
            #report-content { border: none !important; box-shadow: none !important; }
            .print\:block { display: block !important; }
            .print\:hidden { display: none !important; }
            .print\:text-black { color: black !important; }
            .print\:bg-white { background-color: white !important; }
            .print\:border-none { border: none !important; }
        }
    </style>
</x-app-layout>
