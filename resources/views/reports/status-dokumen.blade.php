@extends('reports.layout')
@section('title', 'Laporan Status Verifikasi Dokumen SPM')
@section('description', 'Menampilkan komposisi status dokumen SPM yang sedang diproses maupun telah selesai.')

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
        <div id="chartStatus" class="w-full max-w-lg"></div>
    </div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Status Dokumen</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Jumlah Dokumen SPM</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                        {{ $row->status == 'Cair' ? 'bg-emerald-100 text-emerald-800' : '' }}
                        {{ $row->status == 'Terverifikasi' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $row->status == 'Menunggu Verifikasi' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $row->status == 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $row->status == 'Draft' ? 'bg-slate-100 text-slate-800' : '' }}
                    ">
                        {{ $row->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-center">{{ $row->total }}</td>
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
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + " Dokumen"
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartStatus"), options);
        chart.render();
    });
</script>
@endsection
