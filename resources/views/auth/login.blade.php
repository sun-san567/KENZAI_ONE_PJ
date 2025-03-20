<x-guest-layout>
    <!-- セッションステータス -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- メールアドレス -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" class="text-lg font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- パスワード -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('パスワード')" class="text-lg font-semibold text-gray-700" />

            <x-text-input id="password" class="block mt-1 w-full p-2 border border-gray-300 rounded-md"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- ログイン情報を記憶する -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-700">{{ __('ログイン情報を記憶する') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-blue-600 hover:text-blue-800 transition duration-200" href="{{ route('password.request') }}">
                {{ __('パスワードをお忘れですか？') }}
            </a>
            @endif
            <x-primary-button class="ml-3 px-8 py-3 text-xl bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-md transition duration-200">
                {{ __('ログイン') }}
            </x-primary-button>

        </div>
    </form>
</x-guest-layout>