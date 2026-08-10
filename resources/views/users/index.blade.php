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
                        <a href="#" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition text-sm">
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
