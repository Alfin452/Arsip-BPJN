<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('basts.index') }}" class="p-2 -ml-2 bg-white dark:bg-slate-800 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-3xl text-slate-900 dark:text-white leading-tight">
                {{ __('Detail BAST & Penagihan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-[80vh]">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-3xl border border-slate-100 dark:border-slate-800 h-full">
                @include('bast.partials.modal_detail', ['bast' => $bast])
            </div>
        </div>
    </div>
</x-app-layout>
