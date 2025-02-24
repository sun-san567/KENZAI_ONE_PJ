<div class="w-64 bg-gray-800 text-white h-screen p-4">
    <h2 class="text-lg font-bold">メニュー</h2>
    <ul class="mt-4">
        <li class="p-2 hover:bg-gray-700">
            <a href="{{ route('dashboard') }}">ダッシュボード</a>
        </li>
        <li class="p-2 hover:bg-gray-700">
            <a href="{{ route('phases.index') }}">フェーズ管理</a>
        </li>
        <li class="p-2 hover:bg-gray-700">
            <a href="{{ route('projects.index') }}">PJ管理</a>
        </li>

    </ul>
</div>