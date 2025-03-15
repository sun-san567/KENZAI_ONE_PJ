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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 追加スタイル -->
    <style>
        /* サイドバー幅調整 */
        #sidebar {
            width: 240px;
            border-right: 1px solid #e5e7eb;
            transition: all 0.3s ease-in-out;
        }

        /* メインコンテンツの余白調整 */
        #main-content {
            margin-left: 240px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* サイドバー閉じた時のスタイル */
        #sidebar.closed {
            transform: translateX(-100%);
        }

        #main-content.sidebar-closed {
            margin-left: 0;
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

            #sidebar.closed {
                transform: translateX(-100%);
            }

            #main-content.sidebar-closed {
                margin-left: 0;
            }
        }

        /* オーバーレイスタイル */
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            position: fixed;
            inset: 0;
            z-index: 30;
            display: none;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Alpine.jsの状態管理 -->
    <div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-100">
        <!-- サイドバーオーバーレイ (モバイル時のみ) -->
        <div class="sidebar-overlay"
            x-show="!sidebarOpen"
            @click="sidebarOpen = true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"></div>

        <!-- サイドバー -->
        <div id="sidebar"
            class="fixed h-screen bg-gray-900 text-white shadow-sm z-40"
            :class="{ 'closed': !sidebarOpen }">

            <!-- サイドバー閉じるボタン (モバイル時のみ) -->
            <button @click="sidebarOpen = false"
                class="absolute top-4 right-2 text-white p-2 rounded-full hover:bg-gray-700 md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            @include('layouts.sidebar')
        </div>

        <!-- サイドバー開くボタン (サイドバーが閉じている時のみ表示) -->
        <button @click="sidebarOpen = true"
            class="fixed top-4 left-4 bg-gray-800 text-white p-2 rounded-md z-50 hover:bg-gray-700"
            x-show="!sidebarOpen">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- メインコンテンツ -->
        <div :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="transition-all duration-300 ease-in-out">
            <!-- ナビゲーションバー（もしあれば） -->
            @if(View::exists('layouts.navigation'))
            @include('layouts.navigation')
            @endif

            <!-- ページヘッダー -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-gray-900">
                        @yield('title', 'ダッシュボード')
                    </h1>
                </div>
            </header>

            <!-- メインコンテンツの本体 -->
            <main>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- scripts セクション -->
    @yield('scripts')
</body>

</html>