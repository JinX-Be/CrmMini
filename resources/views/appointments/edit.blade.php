@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Edit appointment</h1>
        <a href="{{ route('appointments.show', $appointment) }}" class="text-sm text-gray-600 hover:underline">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('appointments.update', $appointment) }}">
            @method('PUT')
            @include('appointments._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>

    <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="mt-4"
          onsubmit="return confirm('Delete this appointment?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Delete appointment</button>
    </form>
</div>
@endsection
