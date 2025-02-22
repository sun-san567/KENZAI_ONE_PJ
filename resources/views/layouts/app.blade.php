<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KENZAI-ONE')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- ナビゲーション -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto">
            <a href="{{ url('/') }}" class="text-lg font-bold">KENZAI-ONE</a>
        </div>
    </nav>

    <!-- メインコンテンツ -->
    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

</body>

</html>