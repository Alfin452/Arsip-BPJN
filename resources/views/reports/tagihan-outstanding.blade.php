@extends('reports.layout')
@section('title', 'Laporan Tagihan Outstanding')
@section('description', 'Menampilkan nilai penagihan BAST yang masih berstatus Menunggu Verifikasi atau belum diterbitkan SP2D.')

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
    <div id="chartOutstanding"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Satuan Kerja</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Nilai Tagihan Belum Cair (Rp)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['satker'] }}</td>
                <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400 font-bold text-right">{{ number_format($row['total'], 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="px-6 py-8 text-center text-slate-500">Alhamdulillah, tidak ada tagihan outstanding!</td>
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
                name: 'Nilai Outstanding (Rp)',
                data: {!! json_encode($data) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    borderRadius: 4
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: {!! json_encode($labels) !!},
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
            colors: ['#ef4444']
        };

        var chart = new ApexCharts(document.querySelector("#chartOutstanding"), options);
        chart.render();
    });
</script>
@endsection
