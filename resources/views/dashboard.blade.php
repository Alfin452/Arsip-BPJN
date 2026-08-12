<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Vite Assets for React Dashboard -->
    @push('scripts')
        @viteReactRefresh
        @vite('resources/js/Dashboard.jsx')
    @endpush

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div 
                id="react-dashboard-root"
                data-user-name="{{ Auth::user()->name }}"
                data-user-role="{{ Auth::user()->role }}"
                data-stats="{{ json_encode([
                    'total_spm' => $total_spm ?? 0,
                    'total_sp2d' => $total_sp2d ?? 0,
                    'nilai_spm' => $nilai_spm ?? 0,
                    'nilai_sp2d' => $nilai_sp2d ?? 0,
                    'total_basts' => $total_basts ?? 0,
                    'total_users' => $total_users ?? 0,
                    'total_satker' => $total_satker ?? 0,
                    'total_logs' => $total_logs ?? 0,
                ]) }}"
                data-recent-logs="{{ json_encode($recent_logs ?? []) }}"
                data-sys-info="{{ json_encode($sys_info ?? []) }}"
            ></div>
        </div>
    </div>
</x-app-layout>
