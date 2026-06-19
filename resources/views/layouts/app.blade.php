<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ $niche->label() }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>:root { --brand: {{ $niche->theme() }}; } .bg-brand{background:var(--brand)} .text-brand{color:var(--brand)} .border-brand{border-color:var(--brand)}</style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    @auth
    <header class="bg-brand text-white">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="font-semibold tracking-tight">
                {{ config('app.name') }}
                <span class="opacity-75 font-normal">· {{ $niche->label() }}</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('customers.index') }}" class="hover:underline">{{ $niche->term('customers') }}</a>
                @can('manage-fields')
                    <a href="{{ route('fields.index') }}" class="hover:underline">Fields</a>
                @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="hover:underline">Log out ({{ auth()->user()->name }})</button>
                </form>
            </nav>
        </div>
    </header>
    @endauth

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-100 text-green-800 px-4 py-2 text-sm">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
