@extends('reports.layout')
@section('title', 'Laporan Sisa Waktu Pelaksanaan Kontrak')
@section('description', 'Menampilkan progress berjalannya waktu pelaksanaan proyek fisik dibandingkan dengan total durasi kontrak.')

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
    <div id="chartWaktu"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Nama Paket Pekerjaan</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Total Masa (Hari)</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Hari Berjalan</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">Sisa Hari</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-center">% Berjalan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['paket'] }}</td>
                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 text-center">{{ $row['total_hari'] }}</td>
                <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400 font-medium text-center">{{ $row['hari_berjalan'] }}</td>
                <td class="px-6 py-4 text-sm text-amber-600 dark:text-amber-400 font-medium text-center">{{ $row['sisa_hari'] }}</td>
                <td class="px-6 py-4 text-sm text-center">
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 mt-1">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min($row['persentase'], 100) }}%"></div>
                    </div>
                    <span class="text-xs text-slate-500 mt-1 block">{{ $row['persentase'] }}%</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Tidak ada data untuk tahun {{ $tahun }}</td>
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
                name: 'Hari Berjalan',
                data: {!! json_encode($dataWaktuBerjalan) !!}
            }, {
                name: 'Sisa Waktu',
                data: {!! json_encode($dataWaktuSisa) !!}
            }],
            chart: {
                type: 'bar',
                stacked: true,
                height: {!! count($labels) > 5 ? count($labels) * 50 : 350 !!},
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4
                }
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: {!! json_encode($labels) !!},
                labels: {
                    formatter: function (val) {
                        return val + " Hari"
                    }
                }
            },
            yaxis: {
                title: { text: undefined }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " Hari"
                    }
                }
            },
            fill: { opacity: 1 },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 40
            },
            colors: ['#3b82f6', '#f59e0b']
        };

        var chart = new ApexCharts(document.querySelector("#chartWaktu"), options);
        chart.render();
    });
</script>
@endsection
