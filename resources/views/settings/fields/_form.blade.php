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

@php $isEdit = $field->exists; @endphp

<div class="space-y-4">
    <div>
        <label for="label" class="block text-sm font-medium mb-1">Label <span class="text-red-600">*</span></label>
        <input id="label" name="label" type="text" required
               value="{{ old('label', $field->label) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <div>
        <label for="key" class="block text-sm font-medium mb-1">Key</label>
        @if ($isEdit)
            <input id="key" type="text" value="{{ $field->key }}" disabled
                   class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-gray-500">
            <p class="mt-1 text-xs text-gray-500">The key is fixed — it's how existing data is stored, so it can't be changed.</p>
        @else
            <input id="key" name="key" type="text"
                   value="{{ old('key') }}" placeholder="auto-generated from the label if left blank"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
            <p class="mt-1 text-xs text-gray-500">Lowercase letters, numbers and underscores. Cannot be changed later.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="type" class="block text-sm font-medium mb-1">Type <span class="text-red-600">*</span></label>
            <select id="type" name="type"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
                @foreach (\App\Models\CustomFieldDefinition::types() as $type)
                    <option value="{{ $type }}" @selected(old('type', $field->type) === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium mb-1">Sort order</label>
            <input id="sort_order" name="sort_order" type="number" min="0"
                   value="{{ old('sort_order', $field->sort_order ?? 0) }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
    </div>

    <div>
        <label for="options" class="block text-sm font-medium mb-1">Options <span class="text-gray-400">(select type only)</span></label>
        <textarea id="options" name="options" rows="4" placeholder="One option per line"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">{{ old('options', is_array($field->options) ? implode("\n", $field->options) : '') }}</textarea>
    </div>

    <div>
        <label for="help_text" class="block text-sm font-medium mb-1">Help text</label>
        <input id="help_text" name="help_text" type="text"
               value="{{ old('help_text', $field->help_text) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <label class="inline-flex items-center gap-2 text-sm">
        <input type="hidden" name="required" value="0">
        <input type="checkbox" name="required" value="1" @checked(old('required', $field->required))>
        Required
    </label>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="bg-brand text-white rounded-md px-4 py-2 font-medium hover:opacity-90">
        {{ $submitLabel ?? 'Save' }}
    </button>
    <a href="{{ route('fields.index') }}" class="text-gray-600 hover:underline">Cancel</a>
</div>
