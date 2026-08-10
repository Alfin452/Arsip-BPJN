<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Dokumen SPM') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Data Surat Perintah Membayar (SPM)</h3>
                        <a href="{{ route('spm.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition text-sm">
                            + Unggah SPM
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">No SPM</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Tanggal</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Jenis</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Satker</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Nilai (Rp)</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Status</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spms as $spm)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $spm->nomor_spm }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $spm->tanggal_spm }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $spm->jenis_spm }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $spm->satker->kode_satker ?? '-' }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ number_format($spm->nilai_spm, 2, ',', '.') }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">
                                            @php
                                                $color = 'bg-gray-100 text-gray-800';
                                                if($spm->status == 'Draft') $color = 'bg-gray-100 text-gray-800';
                                                if($spm->status == 'Menunggu Verifikasi') $color = 'bg-yellow-100 text-yellow-800';
                                                if($spm->status == 'Terverifikasi') $color = 'bg-green-100 text-green-800';
                                                if($spm->status == 'Ditolak') $color = 'bg-red-100 text-red-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ $spm->status }}
                                            </span>
                                        </td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">
                                            <a href="{{ route('spm.show', $spm->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="border-b dark:border-gray-700 py-4 px-4 text-center text-sm text-gray-500">Belum ada data SPM yang diunggah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $spms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
