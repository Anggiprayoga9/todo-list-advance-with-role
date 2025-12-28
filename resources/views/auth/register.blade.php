<x-guest-layout>

    <div class="p-5 lg:p-10 bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-100 dark:border-gray-700 w-full max-w-md mx-auto">

        <div class="flex justify-center mt-0">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                Daftar Akun <span class="text-teal-600 dark:text-teal-400">TodoApp</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-base">Buat akun untuk mulai mengelola tugas Anda.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-5">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="name"
                    class="block mt-1 w-full
                           rounded-lg border-gray-300 dark:border-gray-700
                           focus:border-teal-500 focus:ring-teal-500
                           dark:bg-gray-700 dark:text-white
                           shadow-sm transition duration-150"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4 mb-5">
                <x-input-label for="email" :value="__('Alamat Email')" class="text-gray-700 dark:text-gray-300" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full
                           rounded-lg border-gray-300 dark:border-gray-700
                           focus:border-teal-500 focus:ring-teal-500
                           dark:bg-gray-700 dark:text-white
                           shadow-sm transition duration-150"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4 mb-5">
                <x-input-label for="password" :value="__('Kata Sandi')" class="text-gray-700 dark:text-gray-300" />

                <x-text-input
                    id="password"
                    class="block mt-1 w-full
                           rounded-lg border-gray-300 dark:border-gray-700
                           focus:border-teal-500 focus:ring-teal-500
                           dark:bg-gray-700 dark:text-white
                           shadow-sm transition duration-150"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4 mb-6">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-gray-700 dark:text-gray-300" />

                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full
                           rounded-lg border-gray-300 dark:border-gray-700
                           focus:border-teal-500 focus:ring-teal-500
                           dark:bg-gray-700 dark:text-white
                           shadow-sm transition duration-150"
                    type="password"
                    name="password_confirmation"
                    required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-500 dark:text-gray-400
                          hover:text-teal-600 dark:hover:text-teal-400
                          rounded-md focus:outline-none transition duration-150"
                    href="{{ route('login') }}">
                    {{ __('Sudah punya akun?') }}
                </a>

                <x-primary-button
                    class="ms-4 px-6 py-2.5 bg-teal-600 hover:bg-teal-700
                           text-white font-semibold rounded-lg
                           shadow-md shadow-teal-500/50
                           transition duration-300 ease-in-out transform hover:scale-[1.01]">
                    {{ __('Daftar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
