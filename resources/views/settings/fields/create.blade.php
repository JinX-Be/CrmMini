@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">New field</h1>
        <a href="{{ route('fields.index') }}" class="text-sm text-gray-600 hover:underline">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('fields.store') }}">
            @include('settings.fields._form', ['submitLabel' => 'Add field'])
        </form>
    </div>
</div>
@endsection
