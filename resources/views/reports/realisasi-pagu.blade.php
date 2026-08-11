@extends('reports.layout')
@section('title', 'Laporan Realisasi Pagu Anggaran per Satker')
@section('description', 'Membandingkan total pagu anggaran (DIPA) dengan realisasi pencairan (SP2D) untuk tiap Satker pada tahun anggaran terpilih.')

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
    <div id="chartRealisasi"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Satuan Kerja (Satker)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Pagu Anggaran (Rp)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Realisasi (Rp)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Persentase (%)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['satker'] }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-right">{{ number_format($row['pagu'], 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium text-right">{{ number_format($row['realisasi'], 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $row['persentase'] >= 50 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                        {{ $row['persentase'] }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Tidak ada data untuk tahun {{ $tahun }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('page-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Pagu Anggaran',
                data: {!! json_encode($dataPagu) !!}
            }, {
                name: 'Realisasi (SP2D)',
                data: {!! json_encode($dataRealisasi) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: {!! json_encode($labels) !!},
            },
            yaxis: {
                title: { text: 'Rupiah (Rp)' },
                labels: {
                    formatter: function (val) {
                        return (val / 1000000).toFixed(0) + " Juta";
                    }
                }
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            },
            colors: ['#3b82f6', '#10b981']
        };

        var chart = new ApexCharts(document.querySelector("#chartRealisasi"), options);
        chart.render();
    });
</script>
@endsection
