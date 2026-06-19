@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">New {{ $niche->term('customer') }}</h1>
        <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:underline">Back to list</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('customers.store') }}">
            @include('customers._form', ['submitLabel' => 'Create '.$niche->term('customer')])
        </form>
    </div>
</div>
@endsection
