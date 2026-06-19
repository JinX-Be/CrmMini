@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Dashboard</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @php
        $cards = [
            ['label' => $niche->term('customers'), 'value' => $stats['customers']],
            ['label' => 'Upcoming appointments', 'value' => $stats['appointments_upcoming']],
            ['label' => 'Unpaid invoices', 'value' => $stats['invoices_unpaid']],
            ['label' => 'Due to reschedule', 'value' => $stats['due_to_reschedule']],
        ];
    @endphp

    @foreach ($cards as $card)
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-sm text-gray-500">{{ $card['label'] }}</div>
            <div class="text-3xl font-semibold mt-1 text-brand">{{ $card['value'] }}</div>
        </div>
    @endforeach
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold mb-2">Phase 0 complete ✅</h2>
    <p class="text-sm text-gray-600">
        Foundation is in place: auth, niche-aware layout (currently
        <strong>{{ $niche->label() }}</strong>), and the config scaffold.
        Next up — Phase 1: {{ $niche->term('customers') }} with niche custom fields.
    </p>
</div>
@endsection
