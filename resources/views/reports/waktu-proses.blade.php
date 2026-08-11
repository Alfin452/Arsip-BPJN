@extends('reports.layout')
@section('title', 'Laporan Kinerja Waktu Pemrosesan Dokumen (SLA)')
@section('description', 'Menganalisis rata-rata waktu (dalam hari) yang dibutuhkan dari tanggal SPM hingga terbit SP2D per bulan.')

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
    <div id="chartWaktuProses"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Bulan</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Jumlah Dokumen SP2D</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Rata-rata Waktu Proses (Hari)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @foreach($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['bulan'] }} {{ $tahun }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-center">{{ $row['jumlah_dokumen'] }}</td>
                <td class="px-6 py-4 text-sm text-indigo-600 dark:text-indigo-400 font-bold text-center">{{ $row['rata_rata'] }} Hari</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Rata-rata Waktu Proses (Hari)',
                data: {!! json_encode($dataSla) !!}
            }],
            chart: {
                type: 'line',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            stroke: {
                width: 4,
                curve: 'smooth'
            },
            xaxis: {
                categories: {!! json_encode($months) !!},
            },
            yaxis: {
                title: { text: 'Waktu (Hari)' },
            },
            markers: {
                size: 6,
                colors: ['#fff'],
                strokeColors: '#6366f1',
                strokeWidth: 3,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    gradientToColors: ['#3b82f6'],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            colors: ['#8b5cf6']
        };

        var chart = new ApexCharts(document.querySelector("#chartWaktuProses"), options);
        chart.render();
    });
</script>
@endsection
