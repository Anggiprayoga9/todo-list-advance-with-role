<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ Auth::user()->isAdmin() ? 'Panel Tugas Admin' : 'Tugas Saya' }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Selamat datang, {{ Auth::user()->name }}. Anda login sebagai: <span class="font-semibold capitalize">{{ Auth::user()->role }}</span>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-md dark:bg-green-900 dark:text-green-100 dark:border-green-400 transition-all duration-500 rounded-md">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-2xl sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700/70">
                <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white border-b pb-3 border-gray-200 dark:border-gray-700">
                    {{ Auth::user()->isAdmin() ? 'Alokasikan Tugas Baru' : 'Buat Tugas Baru' }}
                </h3>
                <form action="{{ route('todos.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @csrf

                    <div class="md:col-span-2">
                        <x-input-label for="title" :value="__('Judul Tugas')" class="mb-1 text-sm font-semibold" />
                        <x-text-input id="title" name="title" type="text" value="{{ old('title') }}" class="w-full text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400" placeholder="Apa yang harus diselesaikan hari ini?" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    @if (Auth::user()->isAdmin())
                    <div>
                        <x-input-label for="user_id" :value="__('Tugaskan Kepada')" class="mb-1 text-sm font-semibold" />
                        <select id="user_id" name="user_id" required
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-base">
                            <option value="">Pilih User</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                    </div>
                    @endif

                    <div class="col-span-1">
                        <x-input-label for="deadline" :value="__('Deadline (Opsional)')" class="mb-1 text-sm font-semibold" />
                        <x-text-input id="deadline" name="deadline" type="date" value="{{ old('deadline') }}" min="{{ now()->format('Y-m-d') }}" class="w-full text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
                        <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                    </div>

                    <div class="md:col-span-3">
                        <x-input-label for="description" :value="__('Catatan Tambahan (Opsional)')" class="mb-1 text-sm font-semibold" />
                        <textarea name="description" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-base" rows="2" placeholder="Detail atau instruksi tambahan...">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="md:col-span-3 flex justify-end mt-4">
                        <x-primary-button class="px-8 py-3 text-lg font-bold tracking-wider bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            {{ Auth::user()->isAdmin() ? 'Alokasikan Tugas' : 'Simpan Tugas' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-2xl sm:rounded-xl p-6 border border-gray-100 dark:border-gray-700/70">
                <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Daftar Tugas</h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($todos as $todo)
                    <li class="py-5 flex items-start justify-between gap-6 group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 px-3 -mx-3 rounded-lg">
                        <div class="flex items-start gap-4 flex-1">
                            <form action="{{ route('todos.update', $todo->id) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="mt-1 w-7 h-7 rounded-lg border-2 flex items-center justify-center transition-colors duration-200
                                        {{ $todo->completed_at
                                            ? 'bg-indigo-500 border-indigo-500' // Selesai: Warna biru yang konsisten
                                            : 'border-gray-400 dark:border-gray-600 hover:border-indigo-500' }}">
                                    @if ($todo->completed_at)
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    @endif
                                </button>
                            </form>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-semibold {{ $todo->completed_at ? 'line-through text-gray-500 dark:text-gray-600' : 'text-gray-900 dark:text-white' }}">
                                    {{ $todo->title }}
                                </h3>

                                @if($todo->description)
                                <p class="text-sm mt-1 text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $todo->description }}</p>
                                @endif

                                <div class="text-xs mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-gray-500 dark:text-gray-400">

                                    <!-- @if ($todo->deadline)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg font-medium
                                                {{ $todo->completed_at ? 'bg-gray-200 text-gray-600' : (now()->gt($todo->deadline) ? 'bg-red-200 text-red-700 dark:bg-red-900 dark:text-red-300' : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300') }}">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Deadline: {{ $todo->deadline->format('d M Y') }}
                                    </span>
                                    @endif -->

                                    @if ($todo->deadline)
                                    @php
                                    $isOverdue = !$todo->completed_at && now()->gt($todo->deadline);
                                    $badgeClass = 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300'; // Default
                                    $badgeText = 'Deadline: ' . $todo->deadline->format('d M Y');

                                    if ($todo->completed_at) {
                                    $badgeClass = 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';
                                    $badgeText = 'Selesai Tepat Waktu';
                                    if ($todo->completed_at->gt($todo->deadline)) {
                                    $badgeClass = 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
                                    $badgeText = 'Selesai Terlambat';
                                    }
                                    } elseif ($isOverdue) {
                                    $badgeClass = 'bg-red-200 text-red-800 font-bold dark:bg-red-900 dark:text-red-300 animate-pulse'; // Efek mencolok
                                    $badgeText = 'OVERDUE: ' . $todo->deadline->diffForHumans() . ' lalu';
                                    }
                                    @endphp

                                    <span class="inline-flex items-center px-3 py-1 rounded-lg font-medium {{ $badgeClass }}">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $badgeText }}
                                    </span>
                                    @endif

                                    @if (Auth::user()->isAdmin())
                                    <span class="font-medium text-indigo-500">Untuk: {{ $todo->user->name }}</span>
                                    @elseif ($todo->assignedBy)
                                    <span class="font-medium">Dibuat Oleh: <span class="text-yellow-600 dark:text-yellow-400">{{ $todo->assignedBy->name }} (Admin)</span></span>
                                    @endif

                                    @if($todo->completed_at)
                                    <span class="text-green-600 dark:text-green-400 font-semibold flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Selesai: {{ $todo->completed_at->diffForHumans() }}
                                    </span>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- Hanya tampilkan tombol delete jika user memiliki hak delete (didefinisikan di TodoPolicy) --}}
                        @can('delete', $todo)
                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus tugas: {{ $todo->title }}?');" class="flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition opacity-100 sm:opacity-0 sm:group-hover:opacity-100 p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @else
                        {{-- Placeholder kosong agar alignment tidak bergeser --}}
                        <div class="h-6 w-6 p-2 text-transparent flex-shrink-0"></div>
                        @endcan
                    </li>
                    @empty
                    <li class="text-center py-10 text-gray-500 dark:text-gray-400 text-lg font-medium">
                        {{ Auth::user()->isAdmin() ? 'Tidak ada tugas yang sedang aktif dialokasikan.' : 'Anda belum memiliki tugas aktif. Waktunya istirahat!' }}
                    </li>
                    @endforelse
                </ul>

                <div class="mt-6">
                    {{ $todos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
