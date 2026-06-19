@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">New appointment</h1>
        <a href="{{ route('appointments.index') }}" class="text-sm text-gray-600 hover:underline">Back to calendar</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('appointments.store') }}">
            @include('appointments._form', ['submitLabel' => 'Create appointment'])
        </form>
    </div>
</div>
@endsection
