<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ATTENDANCE-APP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header class="bg-black text-white w-full">
        <div class="max-w-[1400px] mx-auto px-8 py-3 flex flex-col md:flex-row items-center justify-between">
            <div class="flex-shrink-0">
                <a href="{{ route('attendance.index') }}">
                    <img src="{{ asset('images/COACHTECH.png') }}" alt="COACHTECH" class="h-6 md:h-8">
                </a>
            </div>
            @if (!Route::is('login') && !Route::is('register') && !Route::is('verification.notice'))
                <div class="flex-1 w-full md:max-w-lg lg:max-w-lg md:mx-12 my-4 md:my-0">
                    <form action="{{ route('attendance.index') }}" method="GET" class="w-full">
                        <input type="hidden" name="tab" value="{{ request()->query('tab') }}">
                        <input type="text" name="keyword" value="{{ request()->query('keyword') }}"
                            class="bg-white text-black px-6 py-2 w-full rounded-sm text-base focus:outline-none"
                            placeholder="なにをお探しですか？">
                    </form>
                </div>
                <nav class="flex items-center gap-4 lg:gap-6 text-sm lg:text-lg flex-shrink-0">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="hover:text-gray-300">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-300">ログイン</a>
                    @endauth
                    <a href="{{ route('attendance.index') }}" class="hover:text-gray-300">マイページ</a>
                    <a href="{{ route('attendance.index') }}" class="bg-white text-black px-4 py-1 rounded-sm">出品</a>
                </nav>
            @endif
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @yield('js')
    <script>
        window.onload = function() {
            const message = document.getElementById('flash-message');
            if (message) {
                setTimeout(() => {
                    message.style.transition = "opacity 1s";
                    message.style.opacity = "0";
                    setTimeout(() => {
                        message.remove();
                    }, 1000);
                }, 3000);
            }
        };
    </script>
</body>
</html>
