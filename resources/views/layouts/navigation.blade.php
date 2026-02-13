    <header class="text-gray-600 body-font border-b border-gray-100 bg-white">
    <div class="w-full flex items-center justify-between px-6 py-4">

        <!-- 左（ロゴ＋ナビ） -->
        <div class="flex items-center border-b border-gray-400 gap-6">
            <a href="{{ route('logs.index') }}" class="flex title-font font-medium items-center text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full"
                viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="ml-3 text-xl font-semibold">🎓Learning Log</span>
            </a>

            <nav class="flex items-center gap-6">
                <a href="{{ route('logs.index') }}"
                    class="{{ request()->routeIs('logs.*') ? 'text-gray-900 ' : 'hover:text-gray-900' }}">
                    学習ログ
                </a>
                <a href="{{ route('logs.create') }}" class="hover:text-gray-900">新規作成</a>
            </nav>
        </div>

        <!-- 右（ユーザー名＋ログアウト） -->
        <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">
            {{ Auth::user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="inline-flex items-center bg-gray-100 border-0 py-1 px-3 hover:bg-gray-200 rounded text-base">
            ログアウト
            </button>
        </form>
        </div>

    </div>
    </header>
