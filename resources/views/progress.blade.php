<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Progress & Laporan Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 border border-gray-100 dark:border-gray-700/70 transition duration-300 hover:shadow-xl">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tugas</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $progressData['total'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 border border-gray-100 dark:border-gray-700/70 transition duration-300 hover:shadow-xl">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $progressData['completed'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 border border-gray-100 dark:border-gray-700/70 transition duration-300 hover:shadow-xl">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum Selesai (Aktif)</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $progressData['pending'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 border border-gray-100 dark:border-gray-700/70 transition duration-300 hover:shadow-xl">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Melewati Deadline</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $progressData['overdue'] }}</p>
                </div>

            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2 sm:mb-0">
                        Detail Tugas (Filter: <span class="capitalize font-mono">{{ $progressData['status'] }}</span>)
                    </h3>

                    <div class="flex space-x-2">
                        @php
                        $buttons = [
                            'all' => 'Semua',
                            'pending' => 'Pending Aktif', // Ubah label agar lebih jelas
                            'completed' => 'Selesai',
                            'overdue' => "Overdue ({$progressData['overdue']})" // Perbaikan string interpolation
                        ];

                        // Logika untuk warna tombol Overdue yang khusus
                        $overdueClass = $progressData['overdue'] > 0
                        ? 'bg-red-300 text-red-600 hover:bg-red-800 hover:text-white'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600';
                        @endphp

                        @foreach($buttons as $key => $label)
                        <a href="{{ route('todos.progress', ['status' => $key]) }}"
                            class="px-4 py-2 text-sm font-medium rounded-full transition duration-150
                            @if($progressData['status'] === $key)
                                {{ $key === 'overdue' ? 'bg-red-600 text-white shadow-md' : 'bg-indigo-600 text-white shadow-md' }}
                            @elseif($key === 'overdue')
                                {{ $overdueClass }}
                            @else
                                {{ 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}
                            @endif
                            ">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penerima</th>
                                @if(Auth::user()->isAdmin())
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pembuat</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($detailTodos as $todo)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $todo->title }}</div>
                                    @if($todo->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate w-48">{{ $todo->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-indigo-500">{{ $todo->user->name }}</div>
                                </td>
                                @if(Auth::user()->isAdmin())
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-yellow-600 dark:text-yellow-400">
                                        {{ $todo->assignedBy?->name ?? 'User Biasa' }}
                                    </div>
                                </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-200">
                                        {{ $todo->deadline ? $todo->deadline->format('d M Y') : '-' }}
                                    </div>
                                </td>

                                {{-- KOLOM STATUS BARU --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($todo->completed_at)
                                        @if($todo->deadline && $todo->completed_at->gt($todo->deadline))
                                            {{-- Selesai Terlambat --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                Selesai Terlambat
                                            </span>
                                        @else
                                            {{-- Selesai Tepat Waktu --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Selesai
                                            </span>
                                        @endif
                                    @elseif($todo->deadline && now()->gt($todo->deadline))
                                        {{-- Overdue (Belum selesai DAN deadline lewat) --}}
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 animate-pulse">
                                            OVERDUE
                                        </span>
                                    @else
                                        {{-- Pending Murni (Aktif dan Aman) --}}
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                {{-- AKHIR KOLOM STATUS BARU --}}

                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isAdmin() ? '5' : '4' }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada tugas dengan status ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $detailTodos->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
