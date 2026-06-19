@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $appointment->starts_at->format('D j M Y, H:i') }}</h1>
            <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full text-white"
                  style="background: {{ $appointment->statusColor() }}">{{ ucfirst($appointment->status) }}</span>
        </div>
        <div class="flex items-center gap-3">
            @if ($appointment->status !== 'completed')
                <form method="POST" action="{{ route('appointments.complete', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <button class="bg-green-600 text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">Mark completed</button>
                </form>
            @endif
            <a href="{{ route('appointments.edit', $appointment) }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">Edit</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">{{ $niche->term('customer') }}</dt>
                <dd class="mt-0.5">
                    @if ($appointment->customer)
                        <a href="{{ route('customers.show', $appointment->customer) }}" class="text-brand hover:underline">{{ $appointment->customer->name }}</a>
                    @else — @endif
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Service</dt>
                <dd class="mt-0.5">{{ $appointment->service?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">When</dt>
                <dd class="mt-0.5">{{ $appointment->starts_at->format('H:i') }}–{{ $appointment->endsAt()->format('H:i') }} ({{ $appointment->duration_minutes }} min)</dd>
            </div>
            <div>
                <dt class="text-gray-500">Price</dt>
                <dd class="mt-0.5">{{ $appointment->price !== null ? '€'.number_format((float) $appointment->price, 2) : '—' }}</dd>
            </div>
        </dl>

        @if ($appointment->notes)
            <div class="border-t border-gray-100 pt-4 mt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-1">Notes</h2>
                <p class="text-sm whitespace-pre-line">{{ $appointment->notes }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('appointments.index') }}" class="inline-block mt-4 text-sm text-gray-600 hover:underline">← Back to calendar</a>
</div>
@endsection
