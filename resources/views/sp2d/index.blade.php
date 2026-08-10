<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Dokumen SP2D') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Data Surat Perintah Pencairan Dana (SP2D)</h3>
                        <a href="{{ route('sp2d.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition text-sm">
                            + Unggah SP2D
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">No SP2D</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Tanggal</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Nilai (Rp)</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">No SPM Terkait</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Status</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sp2ds as $sp2d)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $sp2d->nomor_sp2d }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $sp2d->tanggal_sp2d }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ number_format($sp2d->nilai_sp2d, 2, ',', '.') }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $sp2d->spm ? $sp2d->spm->nomor_spm : '-' }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ ucfirst($sp2d->status) }}
                                            </span>
                                        </td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">
                                            <a href="{{ route('sp2d.show', $sp2d->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border-b dark:border-gray-700 py-4 px-4 text-center text-sm text-gray-500">Belum ada data SP2D yang diunggah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sp2ds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
