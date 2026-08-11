@extends('reports.layout')
@section('title', 'Laporan Distribusi Proyek per Penyedia Jasa')
@section('description', 'Mengetahui penyedia jasa / kontraktor mana yang mendapatkan porsi nilai kontrak terbesar pada tahun berjalan (Top 10).')

@section('filters')
    <div class="w-48">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tahun Anggaran</label>
        <select name="tahun" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500">
            @for($i = date('Y'); $i >= 2020; $i--)
                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
@endsection

@section('chart')
    <div class="flex justify-center">
        <div id="chartDistribusi" class="w-full max-w-2xl"></div>
    </div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Nama Penyedia Jasa</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Jumlah Paket</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Total Nilai Kontrak (Rp)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row->nama_perusahaan }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-center">{{ $row->jumlah_paket }}</td>
                <td class="px-6 py-4 text-sm text-indigo-600 dark:text-indigo-400 font-bold text-right">{{ number_format($row->total_kontrak, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-8 text-center text-slate-500">Tidak ada data untuk tahun {{ $tahun }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: {!! json_encode($data) !!},
            chart: {
                type: 'donut',
                height: 400,
                fontFamily: 'Inter, sans-serif'
            },
            labels: {!! json_encode($labels) !!},
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%"
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: { show: true },
                            value: {
                                show: true,
                                formatter: function (val) {
                                    return "Rp " + (val / 1000000000).toFixed(2) + " Miliar"
                                }
                            }
                        }
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartDistribusi"), options);
        chart.render();
    });
</script>
@endsection
