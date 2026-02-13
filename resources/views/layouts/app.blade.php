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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <nav class="bg-white border-b border-gray-400">
                <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-6 text-gray-600">
                    <a href="{{ route('logs.index') }}" class="hover:text-gray-900">学習ログ</a>
                    <a href="{{ route('logs.create') }}" class="hover:text-gray-900">新規作成</a>
                    <a href="{{ route('logs.trashed') }}" class="hover:text-gray-900">削除済み</a>
                </div>
            </nav>
            <hr>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="max-w-4xl mx-auto p-6">
                @if (session('success'))
                    <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
