<x-guest-layout>

    <div class="p-8 lg:p-10 bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-100 dark:border-gray-700 w-full max-w-md mx-auto">
        <div class="flex justify-center mt-0">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                Masuk ke <span class="text-teal-600 dark:text-teal-400">TodoApp</span>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-base">Kelola dan prioritaskan tugas Anda hari ini.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
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
                    required autofocus autocomplete="username" />
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
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mt-4 mb-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 dark:border-gray-700
                                text-teal-600 dark:text-teal-500 shadow-sm
                                focus:ring-teal-500 dark:focus:ring-offset-gray-800
                                dark:bg-gray-700"
                        name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ingat Saya') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-500 dark:text-gray-400
                              hover:text-teal-600 dark:hover:text-teal-400
                              rounded-md focus:outline-none transition duration-150"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa Kata Sandi?') }}
                </a>
                @endif

                <x-primary-button
                    class="ms-3 px-6 py-2.5 bg-teal-600 hover:bg-teal-700
                           text-white font-semibold rounded-lg
                           shadow-md shadow-teal-500/50
                           transition duration-300 ease-in-out transform hover:scale-[1.01]">
                    {{ __('Masuk') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
