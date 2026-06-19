@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Services</h1>
    <a href="{{ route('services.create') }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">
        + New service
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-4 py-3 font-medium">Name</th>
                <th class="px-4 py-3 font-medium">Price</th>
                <th class="px-4 py-3 font-medium">Duration</th>
                <th class="px-4 py-3 font-medium">Interval</th>
                <th class="px-4 py-3 font-medium">Active</th>
                <th class="px-4 py-3 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $service->name }}</td>
                    <td class="px-4 py-3 text-gray-600">€{{ number_format((float) $service->default_price, 2) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $service->default_duration_minutes }} min</td>
                    <td class="px-4 py-3 text-gray-600">{{ $service->default_interval_weeks ? $service->default_interval_weeks.' wk' : '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $service->active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('services.edit', $service) }}" class="text-gray-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">No services yet. Add your first one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
