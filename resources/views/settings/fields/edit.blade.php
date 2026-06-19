@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Edit field</h1>
        <a href="{{ route('fields.index') }}" class="text-sm text-gray-600 hover:underline">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('fields.update', $field) }}">
            @method('PUT')
            @include('settings.fields._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>

    <form method="POST" action="{{ route('fields.destroy', $field) }}" class="mt-4"
          onsubmit="return confirm('Remove this field? Existing customer data for it is kept but hidden.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Delete field</button>
    </form>
</div>
@endsection
