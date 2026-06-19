@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">{{ $customer->name }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.edit', $customer) }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">Edit</a>
            <a href="{{ route('customers.index') }}" class="text-sm text-gray-600 hover:underline">Back to list</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="mt-0.5">{{ $customer->email ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Phone</dt>
                <dd class="mt-0.5">{{ $customer->phone ?: '—' }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-gray-500">Address</dt>
                <dd class="mt-0.5 whitespace-pre-line">{{ $customer->address ?: '—' }}</dd>
            </div>
        </dl>

        @if (count($niche->customerFields()))
            <div class="border-t border-gray-100 pt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">{{ $niche->label() }} details</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    @foreach ($niche->customerFields() as $field)
                        @php $val = $customer->customField($field->key); @endphp
                        <div>
                            <dt class="text-gray-500">{{ $field->label }}</dt>
                            <dd class="mt-0.5">
                                @if ($field->type === 'checkbox')
                                    {{ $val ? 'Yes' : 'No' }}
                                @else
                                    {{ ($val === null || $val === '') ? '—' : $val }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endif

        @if ($customer->notes)
            <div class="border-t border-gray-100 pt-4">
                <h2 class="text-sm font-semibold text-gray-700 mb-1">Notes</h2>
                <p class="text-sm whitespace-pre-line">{{ $customer->notes }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-semibold">Appointments</h2>
            <a href="{{ route('appointments.create', ['customer' => $customer->id]) }}" class="text-sm text-brand hover:underline">+ New appointment</a>
        </div>
        @forelse ($customer->appointments as $appt)
            <a href="{{ route('appointments.show', $appt) }}" class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 hover:bg-gray-50 -mx-2 px-2 rounded">
                <span class="text-sm">{{ $appt->starts_at->format('D j M Y, H:i') }}
                    @if ($appt->service)<span class="text-gray-500">· {{ $appt->service->name }}</span>@endif
                </span>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full text-white" style="background: {{ $appt->statusColor() }}">{{ ucfirst($appt->status) }}</span>
            </a>
        @empty
            <p class="text-sm text-gray-500">No appointments yet.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="mt-4"
          onsubmit="return confirm('Delete this {{ strtolower($niche->term('customer')) }}? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:underline">Delete {{ $niche->term('customer') }}</button>
    </form>
</div>
@endsection
