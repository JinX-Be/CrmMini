@extends('layouts.app')

@section('content')
<div class="max-w-sm mx-auto mt-10 bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-semibold mb-1">Sign in</h1>
    <p class="text-sm text-gray-500 mb-6">{{ config('app.name') }} · {{ $niche->label() }}</p>

    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-100 text-red-800 px-4 py-2 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-md border-gray-300 border px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="password">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-md border-gray-300 border px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button type="submit" class="w-full bg-brand text-white rounded-md py-2 font-medium hover:opacity-90">
            Sign in
        </button>
    </form>
</div>
@endsection
