@csrf

@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-100 text-red-800 px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $startsAt = old('starts_at', optional($appointment->starts_at)?->format('Y-m-d\TH:i'));
@endphp

<div class="space-y-4">
    <div>
        <label for="customer_id" class="block text-sm font-medium mb-1">{{ $niche->term('customer') }} <span class="text-red-600">*</span></label>
        <select id="customer_id" name="customer_id" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
            <option value="">— Select —</option>
            @foreach ($customers as $c)
                <option value="{{ $c->id }}" @selected((int) old('customer_id', $appointment->customer_id) === $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
        @if ($customers->isEmpty())
            <p class="mt-1 text-xs text-gray-500">No {{ strtolower($niche->term('customers')) }} yet — <a href="{{ route('customers.create') }}" class="text-brand hover:underline">add one</a> first.</p>
        @endif
    </div>

    <div>
        <label for="service_id" class="block text-sm font-medium mb-1">Service</label>
        <select id="service_id" name="service_id"
                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
            <option value="">— None —</option>
            @foreach ($services as $s)
                <option value="{{ $s->id }}"
                        data-duration="{{ $s->default_duration_minutes }}"
                        data-price="{{ $s->default_price }}"
                        @selected((int) old('service_id', $appointment->service_id) === $s->id)>{{ $s->name }} (€{{ number_format((float) $s->default_price, 2) }})</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-1">
            <label for="starts_at" class="block text-sm font-medium mb-1">Starts <span class="text-red-600">*</span></label>
            <input id="starts_at" name="starts_at" type="datetime-local" required value="{{ $startsAt }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <div>
            <label for="duration_minutes" class="block text-sm font-medium mb-1">Duration (min) <span class="text-red-600">*</span></label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="5" required
                   value="{{ old('duration_minutes', $appointment->duration_minutes ?: 60) }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <div>
            <label for="price" class="block text-sm font-medium mb-1">Price (€)</label>
            <input id="price" name="price" type="number" step="0.01" min="0"
                   value="{{ old('price', $appointment->price) }}" placeholder="Defaults to service price"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
    </div>

    <div>
        <label for="status" class="block text-sm font-medium mb-1">Status <span class="text-red-600">*</span></label>
        <select id="status" name="status"
                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $appointment->status) === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="notes" class="block text-sm font-medium mb-1">Notes</label>
        <textarea id="notes" name="notes" rows="3"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">{{ old('notes', $appointment->notes) }}</textarea>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="bg-brand text-white rounded-md px-4 py-2 font-medium hover:opacity-90">
        {{ $submitLabel ?? 'Save' }}
    </button>
    <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:underline">Cancel</a>
</div>

<script>
    // Convenience: when a service is picked, fill duration/price if left blank.
    document.getElementById('service_id')?.addEventListener('change', function () {
        var opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) return;
        var dur = document.getElementById('duration_minutes');
        var price = document.getElementById('price');
        if (dur && !dur.value) dur.value = opt.dataset.duration || '';
        if (price && !price.value) price.value = opt.dataset.price || '';
    });
</script>
