@extends('reports.layout')
@section('title', 'Laporan Serapan Anggaran Paket Pekerjaan')
@section('description', 'Melihat progres pembayaran tiap paket pekerjaan dengan membandingkan Nilai Kontrak dengan total BAST yang terverifikasi.')

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
    <div id="chartSerapan"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Nama Paket Pekerjaan</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Nilai Kontrak (Rp)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Serapan/BAST (Rp)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Persentase (%)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['paket'] }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-right">{{ number_format($row['nilai_kontrak'], 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium text-right">{{ number_format($row['serapan'], 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $row['persentase'] >= 80 ? 'bg-emerald-100 text-emerald-800' : ($row['persentase'] >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
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
                name: 'Nilai Kontrak',
                data: {!! json_encode($dataKontrak) !!}
            }, {
                name: 'Serapan (BAST Terverifikasi)',
                data: {!! json_encode($dataSerapan) !!}
            }],
            chart: {
                type: 'bar',
                height: {!! count($labels) > 5 ? count($labels) * 50 : 350 !!},
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    dataLabels: { position: 'top' },
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: { fontSize: '10px', colors: ['#fff'] },
                formatter: function(val) {
                    return (val / 1000000).toFixed(0) + "Jt";
                }
            },
            stroke: { show: true, width: 1, colors: ['#fff'] },
            xaxis: {
                categories: {!! json_encode($labels) !!},
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
            colors: ['#94a3b8', '#059669']
        };

        var chart = new ApexCharts(document.querySelector("#chartSerapan"), options);
        chart.render();
    });
</script>
@endsection
