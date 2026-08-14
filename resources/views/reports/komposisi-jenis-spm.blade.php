@extends('reports.layout')
@section('title', 'Laporan Komposisi Jenis SPM')
@section('description', 'Menampilkan persentase jenis SPM yang telah diterbitkan (LS, UP, TUP, GU).')

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
        <div id="chartJenis" class="w-full max-w-lg"></div>
    </div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Jenis SPM</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Jumlah Diterbitkan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row->nama_jenis ?? $row->jenis_spm }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-center font-semibold">{{ $row->total }} Dokumen</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="px-6 py-8 text-center text-slate-500">Tidak ada data untuk tahun {{ $tahun }}</td>
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
                type: 'pie',
                height: 350,
                fontFamily: 'Inter, sans-serif'
            },
            labels: {!! json_encode($labels) !!},
            colors: ['#0ea5e9', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + " SPM"
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartJenis"), options);
        chart.render();
    });
</script>
@endsection
