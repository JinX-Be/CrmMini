@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-2">
    <h1 class="text-2xl font-semibold">Custom fields</h1>
    <a href="{{ route('fields.create') }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">
        + New field
    </a>
</div>
<p class="text-sm text-gray-500 mb-6">
    Fields shown on every {{ strtolower($niche->term('customer')) }} in the
    <strong>{{ $niche->label() }}</strong> niche.
</p>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-4 py-3 font-medium">Order</th>
                <th class="px-4 py-3 font-medium">Label</th>
                <th class="px-4 py-3 font-medium">Key</th>
                <th class="px-4 py-3 font-medium">Type</th>
                <th class="px-4 py-3 font-medium">Required</th>
                <th class="px-4 py-3 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($fields as $field)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $field->sort_order }}</td>
                    <td class="px-4 py-3 font-medium">{{ $field->label }}</td>
                    <td class="px-4 py-3"><code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">{{ $field->key }}</code></td>
                    <td class="px-4 py-3 text-gray-600">{{ $field->type }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $field->required ? 'Yes' : '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('fields.edit', $field) }}" class="text-gray-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">No custom fields yet. Add your first one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
