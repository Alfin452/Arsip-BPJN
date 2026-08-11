@extends('reports.layout')
@section('title', 'Laporan Tren Pencairan Dana Bulanan')
@section('description', 'Menampilkan pergerakan nilai pencairan dana (SP2D) dari bulan ke bulan sepanjang tahun anggaran.')

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
    <div id="chartTren"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Bulan</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Total Pencairan (Rp)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['bulan'] }} {{ $tahun }}</td>
                <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 font-bold text-right">{{ number_format($row['total'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bg-slate-50 dark:bg-slate-900/80 font-bold">
                <td class="px-6 py-4 text-sm text-slate-800 dark:text-slate-200">TOTAL TAHUN {{ $tahun }}</td>
                <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 text-right">{{ number_format(array_sum(array_column($tableData, 'total')), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Total Pencairan (Rp)',
                data: {!! json_encode($dataTrend) !!}
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: {!! json_encode($months) !!},
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return (val / 1000000).toFixed(0) + " Jt";
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#0ea5e9']
        };

        var chart = new ApexCharts(document.querySelector("#chartTren"), options);
        chart.render();
    });
</script>
@endsection
