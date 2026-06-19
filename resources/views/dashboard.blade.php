@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Dashboard</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @php
        $cards = [
            ['label' => $niche->term('customers'), 'value' => $stats['customers'], 'href' => route('customers.index')],
            ['label' => 'Upcoming appointments', 'value' => $stats['appointments_upcoming'], 'href' => route('appointments.index')],
            ['label' => 'Unpaid invoices', 'value' => $stats['invoices_unpaid'], 'href' => null],
            ['label' => 'Due to reschedule', 'value' => $stats['due_to_reschedule'], 'href' => null],
        ];
    @endphp

    @foreach ($cards as $card)
        @php $tag = $card['href'] ? 'a' : 'div'; @endphp
        <{{ $tag }} @if($card['href']) href="{{ $card['href'] }}" @endif
            class="block bg-white rounded-xl shadow p-5 {{ $card['href'] ? 'hover:shadow-md transition' : '' }}">
            <div class="text-sm text-gray-500">{{ $card['label'] }}</div>
            <div class="text-3xl font-semibold mt-1 text-brand">{{ $card['value'] }}</div>
        </{{ $tag }}>
    @endforeach
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold mb-2">Phase 1 — {{ $niche->term('customers') }}</h2>
    <p class="text-sm text-gray-600">
        Manage your {{ strtolower($niche->term('customers')) }} with
        {{ $niche->label() }}-specific fields.
        <a href="{{ route('customers.index') }}" class="text-brand hover:underline">Go to {{ $niche->term('customers') }} →</a>
    </p>
</div>
@endsection
