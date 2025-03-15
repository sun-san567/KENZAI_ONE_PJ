<!-- ハンバーガーメニュー（スマホ用） -->
<button @click="open = !open" class="fixed top-4 left-4 sm:hidden text-white bg-gray-800 px-3 py-2 rounded-md">
    ☰
</button>

<!-- サイドバー -->
<aside :class="sidebarOpen ? 'w-64 bg-gray-800' : 'w-16 bg-gray-900'"
    class="fixed inset-y-0 left-0 transition-all duration-500 ease-in-out overflow-hidden z-10">

    <!-- 開閉ボタン - 修正：絶対配置で左側中央に -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="absolute top-1/2 -translate-y-1/2 left-0 p-2 text-white rounded-r border border-gray-700 bg-gray-700 hover:bg-gray-600 focus:outline-none z-20 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 transition-transform duration-500"
            :class="sidebarOpen ? '' : 'transform rotate-180'"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- サイドバーヘッダー (ロゴ) -->
    <div class="flex items-center px-4 py-3 border-b border-gray-700">
        <!-- ロゴまたはサイト名 -->
        <h2 :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'"
            class="text-xl font-bold text-white transition-all duration-500">
            管理システム
        </h2>
    </div>

    <!-- メニューリスト -->
    <nav class="mt-4">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('dashboard') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">🏠</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">ダッシュボード</span>
                </a>
            </li>
            <li>
                <a href="{{ route('company.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('company.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">🏢</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">会社情報管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('users.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">👨‍💼</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">ユーザー管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('phases.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('phases.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">🔄</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">フェーズ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('categories.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">🔖</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">カテゴリ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('clients.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">📋</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">顧客管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('projects.index') }}"
                    :class="sidebarOpen ? 'justify-start px-4' : 'justify-center px-0'"
                    class="flex items-center h-12 text-white rounded-md transition-all duration-500 
                    {{ request()->routeIs('projects.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                    <span class="text-xl flex-shrink-0" :class="sidebarOpen ? 'ml-0' : 'mx-auto'">📂</span>
                    <span class="ml-2 transition-all duration-500"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">PJ管理</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- サイドバー外クリックで閉じる -->
<div x-show="open" x-cloak @click="open = false" class="fixed inset-0 bg-black opacity-50 sm:hidden"></div>

<!-- メインコンテンツ -->
<!-- <main x-bind:class="open ? 'ml-64' : 'ml-0'" class="transition-all duration-300">
    <h1>案件管理</h1>
</main> -->