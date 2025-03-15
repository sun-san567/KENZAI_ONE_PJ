<!-- ハンバーガーメニュー（スマホ用） -->
<button @click="open = !open" class="fixed top-4 left-4 sm:hidden text-white bg-gray-800 px-3 py-2 rounded-md">
    ☰
</button>

<!-- サイドバー -->
<aside :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="fixed inset-y-0 left-0 bg-gray-900 transition-all duration-300 ease-in-out overflow-hidden z-10">

    <!-- サイドバーヘッダー (ロゴと開閉ボタン) -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-800">
        <!-- ロゴまたはサイト名 -->
        <h2 :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'"
            class="text-xl font-bold text-white transition-all duration-300">
            管理システム
        </h2>

        <!-- 開閉ボタン - 修正版 -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-1 text-white rounded hover:bg-gray-800 focus:outline-none">
            <!-- 単純化したアイコン -->
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 transition-transform duration-300"
                :class="sidebarOpen ? '' : 'transform rotate-180'"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- メニューリスト -->
    <nav class="mt-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">🏠</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">ダッシュボード</span>
                </a>
            </li>
            <li>
                <a href="{{ route('company.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('company.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">🏢</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">会社情報管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('users.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">👨‍💼</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">ユーザー管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('phases.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('phases.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">🔄</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">フェーズ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('categories.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">🔖</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">カテゴリ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('clients.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">📋</span>
                    <span class="ml-2 transition-all duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">顧客管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('projects.index') }}"
                    class="block px-4 py-2 flex items-center text-white rounded-md 
                    {{ request()->routeIs('projects.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    <span class="text-xl flex-shrink-0">📂</span>
                    <span class="ml-2 transition-all duration-300"
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