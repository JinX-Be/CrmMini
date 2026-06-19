@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Edit service</h1>
        <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:underline">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('services.update', $service) }}">
            @method('PUT')
            @include('services._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>

    <form method="POST" action="{{ route('services.destroy', $service) }}" class="mt-4"
          onsubmit="return confirm('Delete this service?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Delete service</button>
    </form>
</div>
@endsection
