<x-guest-layout>
    <div class="mb-4 text-sm text-gray-700">
        {{ __('メールアドレスを入力してください。') }}<br>
        {{ __('パスワードを再設定できます。') }}
    </div>

    <!-- セッションステータス -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- メールアドレス -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full p-2 border border-gray-300 rounded-md text-sm"
                type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                {{ __('ログインページへ戻る') }}
            </a>

            <x-primary-button class="ml-3 px-8 py-3 text-xl bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-md transition duration-200">
                {{ __('送信') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>