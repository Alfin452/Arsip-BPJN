@props(['options' => [], 'placeholder' => 'Pilih...', 'searchable' => false])

<div x-data="{
        open: false,
        search: '',
        value: '{{ $attributes->get('value', '') }}',
        options: {{ json_encode($options) }},
        
        get selectedLabel() {
            const opt = this.options.find(o => String(o.value) === String(this.value));
            return opt ? opt.label : '{!! addslashes($placeholder) !!}';
        },
        
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        
        init() {
            if (this.$refs.searchInput) {
                this.search = this.selectedLabel;
                this.$watch('value', () => {
                    this.search = this.selectedLabel;
                });
                this.$watch('open', (isOpen) => {
                    if (isOpen) {
                        this.search = '';
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    } else {
                        this.search = this.selectedLabel;
                    }
                });
            }
        }
    }"
    x-modelable="value"
    {{ $attributes->whereStartsWith('x-model') }}
    @click.away="open = false"
    class="relative w-full">
    
    <!-- Input tersembunyi untuk form submit (hanya jika ada attribute name) -->
    @if($attributes->has('name'))
        <input type="hidden" name="{{ $attributes->get('name') }}" :value="value">
    @endif

    @if($searchable)
        <!-- Combobox Mode -->
        <div class="relative w-full flex items-center justify-between text-left bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all duration-200 cursor-text" @click="open = true">
            <input x-ref="searchInput" 
                   type="text"
                   x-model="search"
                   @focus="open = true"
                   @keydown.escape="open = false"
                   :placeholder="selectedLabel || '{{ $placeholder }}'"
                   :class="!value ? 'text-slate-500 dark:text-slate-400' : 'text-slate-800 dark:text-slate-200'"
                   class="w-full bg-transparent border-none pl-4 pr-10 py-2.5 text-sm focus:ring-0 placeholder-slate-400 dark:placeholder-slate-500 font-medium truncate">
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400" @click="open = !open">
                <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>
    @else
        <!-- Standard Dropdown Mode -->
        <button @click="open = !open" type="button" 
                class="relative w-full flex items-center justify-between text-left bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm shadow-sm hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200">
            <span x-text="selectedLabel" :class="!value ? 'text-slate-500 dark:text-slate-400' : 'text-slate-800 dark:text-slate-200'" class="truncate font-medium"></span>
            <span class="pointer-events-none flex items-center ml-2 text-slate-400">
                <svg class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </button>
    @endif

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden" 
         style="display: none;">

        <ul class="max-h-60 overflow-y-auto py-1 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-600">
            <!-- Pilihan Kosong (opsional) -->
            <li @click="value = ''; open = false; search = ''" 
                class="cursor-pointer select-none relative py-2.5 pl-4 pr-9 text-sm transition-colors"
                :class="value === '' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                <span class="block truncate">{{ $placeholder }}</span>
                <span x-show="value === ''" class="absolute inset-y-0 right-0 flex items-center pr-3 text-blue-600 dark:text-blue-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </span>
            </li>

            <template x-for="option in filteredOptions" :key="option.value">
                <li @click="value = option.value; open = false; search = ''" 
                    class="cursor-pointer select-none relative py-2.5 pl-4 pr-9 text-sm transition-colors"
                    :class="String(value) === String(option.value) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                    <span class="block truncate" x-text="option.label"></span>
                    <span x-show="String(value) === String(option.value)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-blue-600 dark:text-blue-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </span>
                </li>
            </template>
            
            <li x-show="filteredOptions.length === 0" class="cursor-default select-none relative py-3 px-4 text-sm text-slate-500 text-center">
                Tidak ditemukan
            </li>
        </ul>
    </div>
</div>
