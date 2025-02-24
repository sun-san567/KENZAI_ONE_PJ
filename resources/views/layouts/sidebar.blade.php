<aside x-data="{ open: true }"
    class="bg-gray-900 text-white fixed top-0 left-0 h-full w-64 transition-transform duration-300 transform"
    :class="open ? 'translate-x-0' : '-translate-x-64'">

    <!-- サイドバートップ -->
    <div class="p-5 border-b border-gray-700 flex justify-between items-center">
        <h1 class="text-lg font-bold">KENZAI-ONE</h1>
        <button @click="open = !open" class="sm:hidden text-white">✖️</button>
    </div>

    <!-- メニューリスト -->
    <nav class="mt-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    🏠 <span>ダッシュボード</span>
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('clients.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    📋 <span>顧客管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('companies.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('companies.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    🏢 <span>会社情報管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('departments.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('departments.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    👥 <span>部門管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('projects.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('projects.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    📂 <span>PJ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('phases.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('phases.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    🔄 <span>フェーズ管理</span>
                </a>
            </li>
            <li>
                <a href="{{ route('employees.index') }}"
                    class="block px-5 py-3 flex items-center space-x-2 text-white rounded-md 
                    {{ request()->routeIs('employees.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                    👨‍💼 <span>担当者管理</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>