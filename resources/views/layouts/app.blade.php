<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KENZAI-ONE')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <!-- ヘッダー -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto">
            <a href="{{ url('/') }}" class="text-lg font-bold">KENZAI-ONE</a>
        </div>
    </nav>

    <!-- レイアウト全体のコンテナ -->
    <div class="flex flex-1 min-h-screen">
        <!-- サイドバー -->
        <aside class="w-60 min-h-screen bg-gray-900 text-white">
            <div class="p-5">
                <h2 class="text-lg font-bold">メニュー</h2>
                <ul class="mt-4 space-y-2">
                    <li><a href="{{ route('companies.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">自社情報管理</a></li>
                    <li><a href="{{ route('departments.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">部門管理</a></li>
                    <li><a href="{{ route('employees.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">担当者管理</a></li>
                    <li><a href="{{ route('dashboard') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">ダッシュボード</a></li>
                    <li><a href="{{ route('projects.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">PJ管理</a></li>
                    <li><a href="{{ route('phases.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">フェーズ管理</a></li>
                    <!-- 追加部分: 顧客登録ページへのリンク -->
                    <li><a href="{{ route('clients.index') }}" class="block text-white hover:bg-gray-700 px-3 py-2 rounded">顧客登録</a></li>
                </ul>
            </div>
        </aside>

        <!-- メインコンテンツ -->
        <main class="flex-1 p-6 bg-white rounded-lg shadow-md overflow-auto">
            @yield('content')
        </main>
    </div>

</body>

</html>