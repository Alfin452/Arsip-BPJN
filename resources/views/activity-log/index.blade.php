<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
            {{ __('Log Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Daftar Log Aktivitas</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lacak semua perubahan dan aksi yang dilakukan pengguna.</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-sm">
                                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Waktu</th>
                                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Aktor</th>
                                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Aksi</th>
                                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Tipe Objek</th>
                                <th class="px-6 py-4 font-semibold border-b border-slate-200 dark:border-slate-700">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($activities as $activity)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $activity->created_at->translatedFormat('d M Y') }}</span>
                                        <span class="text-xs">{{ $activity->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                            {{ substr($activity->causer->name ?? 'System', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $activity->causer->name ?? 'Sistem Otomatis' }}</p>
                                            <p class="text-xs text-slate-500">{{ $activity->causer->role ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $eventClass = match($activity->event) {
                                            'created' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                            'updated' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                                            'deleted' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
                                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-400'
                                        };
                                        $eventName = match($activity->event) {
                                            'created' => 'Dibuat',
                                            'updated' => 'Diedit',
                                            'deleted' => 'Dihapus',
                                            default => ucfirst($activity->event)
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eventClass }}">
                                        {{ $eventName }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-mono">
                                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $activity->description }}
                                    @if($activity->properties->has('attributes'))
                                        <button onclick="toggleDetails({{ $activity->id }})" class="ml-2 text-blue-600 hover:underline text-xs">Lihat Perubahan</button>
                                        <div id="details-{{ $activity->id }}" class="hidden mt-2 p-3 bg-slate-50 dark:bg-slate-900 rounded-lg text-xs overflow-x-auto border border-slate-200 dark:border-slate-800">
                                            @if($activity->properties->has('old'))
                                                <div class="mb-2">
                                                    <span class="font-semibold text-rose-500">Sebelum:</span>
                                                    <pre class="mt-1 text-slate-500">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-semibold text-emerald-500">Sesudah:</span>
                                                <pre class="mt-1 text-slate-500">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p>Belum ada riwayat aktivitas tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts should be placed outside or inside a named slot if the layout supports it, but x-app-layout usually has an x-slot name="scripts" or we just put it inline -->
    <script>
        function toggleDetails(id) {
            const el = document.getElementById('details-' + id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
