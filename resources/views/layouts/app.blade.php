<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 追加スタイル -->
    <style>
        /* サイドバー幅調整 */
        #sidebar {
            width: 240px;
            border-right: 1px solid #e5e7eb;
            transition: width 0.3s ease-in-out;
        }

        /* メインコンテンツの余白調整 */
        #main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* モバイル対応 */
        @media (max-width: 1024px) {
            #sidebar {
                width: 56px;
            }

            #main-content {
                margin-left: 56px;
            }

            .sidebar-text {
                display: none;
            }
        }
    </style>
</head>

<body class="font-sans antialiased flex">

    <!-- サイドバー -->
    <div id="sidebar" class="fixed h-screen bg-gray-900 text-white shadow-sm">
        @include('layouts.sidebar')
    </div>

    <!-- メインコンテンツ -->
    <div id="main-content" class="min-h-screen bg-gray-100 flex-1">

        <!-- ヘッダー -->
        @include('layouts.navigation')

        <!-- ページヘッダー -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- ページコンテンツ -->
        <main class="p-6">
            @yield('content')
        </main>

    </div>

    <!-- ここが重要: scripts セクション -->
    @yield('scripts')

</body>

</html>