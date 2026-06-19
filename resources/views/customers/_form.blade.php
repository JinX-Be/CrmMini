@csrf

@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-100 text-red-800 px-4 py-3 text-sm">
        <p class="font-medium mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium mb-1">Name <span class="text-red-600">*</span></label>
        <input id="name" name="name" type="text" required
               value="{{ old('name', $customer->name) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1">Email</label>
        <input id="email" name="email" type="email"
               value="{{ old('email', $customer->email) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium mb-1">Phone</label>
        <input id="phone" name="phone" type="text"
               value="{{ old('phone', $customer->phone) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <div class="md:col-span-2">
        <label for="address" class="block text-sm font-medium mb-1">Address</label>
        <textarea id="address" name="address" rows="2"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">{{ old('address', $customer->address) }}</textarea>
    </div>
</div>

@if (count($niche->customerFields()))
    <h3 class="mt-6 mb-2 text-sm font-semibold text-gray-700">{{ $niche->label() }} details</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($niche->customerFields() as $field)
            @php
                $key = $field->key;
                $name = 'custom['.$key.']';
                $value = old('custom.'.$key, $customer->customField($key));
            @endphp
            <div class="{{ $field->type === 'textarea' ? 'md:col-span-2' : '' }}">
                <label for="cf_{{ $key }}" class="block text-sm font-medium mb-1">
                    {{ $field->label }}
                    @if ($field->required) <span class="text-red-600">*</span> @endif
                </label>

                @switch($field->type)
                    @case('number')
                        <input id="cf_{{ $key }}" name="{{ $name }}" type="number" step="any"
                               value="{{ $value }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
                        @break

                    @case('textarea')
                        <textarea id="cf_{{ $key }}" name="{{ $name }}" rows="2"
                                  class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">{{ $value }}</textarea>
                        @break

                    @case('select')
                        <select id="cf_{{ $key }}" name="{{ $name }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
                            <option value="">—</option>
                            @foreach ($field->options as $option)
                                <option value="{{ $option }}" @selected((string) $value === (string) $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('checkbox')
                        <input type="hidden" name="{{ $name }}" value="0">
                        <label class="inline-flex items-center gap-2 mt-1 text-sm">
                            <input id="cf_{{ $key }}" name="{{ $name }}" type="checkbox" value="1" @checked((bool) $value)>
                            Yes
                        </label>
                        @break

                    @default
                        <input id="cf_{{ $key }}" name="{{ $name }}" type="text"
                               value="{{ $value }}"
                               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
                @endswitch

                @if ($field->helpText)
                    <p class="mt-1 text-xs text-gray-500">{{ $field->helpText }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

<div class="md:col-span-2 mt-4">
    <label for="notes" class="block text-sm font-medium mb-1">Notes</label>
    <textarea id="notes" name="notes" rows="3"
              class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">{{ old('notes', $customer->notes) }}</textarea>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="bg-brand text-white rounded-md px-4 py-2 font-medium hover:opacity-90">
        {{ $submitLabel ?? 'Save' }}
    </button>
    <a href="{{ route('customers.index') }}" class="text-gray-600 hover:underline">Cancel</a>
</div>
