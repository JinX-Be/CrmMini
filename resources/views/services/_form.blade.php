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

<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium mb-1">Name <span class="text-red-600">*</span></label>
        <input id="name" name="name" type="text" required value="{{ old('name', $service->name) }}"
               class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="default_price" class="block text-sm font-medium mb-1">Price (€) <span class="text-red-600">*</span></label>
            <input id="default_price" name="default_price" type="number" step="0.01" min="0" required
                   value="{{ old('default_price', $service->default_price) }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <div>
            <label for="default_duration_minutes" class="block text-sm font-medium mb-1">Duration (min) <span class="text-red-600">*</span></label>
            <input id="default_duration_minutes" name="default_duration_minutes" type="number" min="5" required
                   value="{{ old('default_duration_minutes', $service->default_duration_minutes) }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
        <div>
            <label for="default_interval_weeks" class="block text-sm font-medium mb-1">Reschedule every (weeks)</label>
            <input id="default_interval_weeks" name="default_interval_weeks" type="number" min="1"
                   value="{{ old('default_interval_weeks', $service->default_interval_weeks) }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:border-brand">
        </div>
    </div>

    <label class="inline-flex items-center gap-2 text-sm">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" @checked(old('active', $service->active ?? true))>
        Active
    </label>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="bg-brand text-white rounded-md px-4 py-2 font-medium hover:opacity-90">
        {{ $submitLabel ?? 'Save' }}
    </button>
    <a href="{{ route('services.index') }}" class="text-gray-600 hover:underline">Cancel</a>
</div>
