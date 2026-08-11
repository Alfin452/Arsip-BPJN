@extends('reports.layout')
@section('title', 'Laporan Kinerja Penyerapan PPK')
@section('description', 'Menampilkan perbandingan total nilai pencairan yang dikelola oleh masing-masing Pejabat Pembuat Komitmen (PPK).')

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
    <div id="chartPpk"></div>
@endsection

@section('table')
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-sm">
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Nama PPK</th>
                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700 text-right">Realisasi Penyerapan (Rp)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($tableData as $row)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $row['ppk'] }}</td>
                <td class="px-6 py-4 text-sm text-indigo-600 dark:text-indigo-400 font-medium text-right">{{ number_format($row['realisasi'], 0, ',', '.') }}</td>
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
            series: [{
                name: 'Total Penyerapan (Rp)',
                data: {!! json_encode($data) !!}
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
                    borderRadius: 4
                }
            },
            dataLabels: { enabled: false },
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
            colors: ['#6366f1']
        };

        var chart = new ApexCharts(document.querySelector("#chartPpk"), options);
        chart.render();
    });
</script>
@endsection
