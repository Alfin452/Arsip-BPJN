<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="p-6">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white" required value="{{ old('name') }}">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" name="email" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white" required value="{{ old('email') }}">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Peran (Role)</label>
                            <select name="role" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white" required>
                                <option value="karyawan" {{ old('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="atasan" {{ old('role') == 'atasan' ? 'selected' : '' }}>Atasan</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
                            <input type="password" name="password" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white" required>
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white" required>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan Pengguna</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>