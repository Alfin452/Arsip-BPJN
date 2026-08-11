<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Daftar Akun Pengguna</h3>
                        <a href="{{ route('users.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition text-sm">
                            + Tambah Pengguna
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Nama</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Email</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Role</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm">Terdaftar Sejak</th>
                                    <th class="border-b dark:border-gray-700 py-3 px-4 font-semibold text-sm text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $user->name }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $user->email }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : ($user->role == 'atasan' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="border-b dark:border-gray-700 py-3 px-4 text-sm text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 rounded-md transition" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
