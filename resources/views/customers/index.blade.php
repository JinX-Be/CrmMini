@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">{{ $niche->term('customers') }}</h1>
    <a href="{{ route('customers.create') }}" class="bg-brand text-white rounded-md px-4 py-2 text-sm font-medium hover:opacity-90">
        + New {{ $niche->term('customer') }}
    </a>
</div>

<form method="GET" action="{{ route('customers.index') }}" class="mb-4">
    <input type="text" name="q" value="{{ $q }}" placeholder="Search by name, email or phone…"
           class="w-full max-w-md rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:border-brand">
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-4 py-3 font-medium">Name</th>
                <th class="px-4 py-3 font-medium">Email</th>
                <th class="px-4 py-3 font-medium">Phone</th>
                <th class="px-4 py-3 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('customers.show', $customer) }}" class="font-medium text-brand hover:underline">{{ $customer->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $customer->email ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $customer->phone ?: '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-gray-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                        @if ($q !== '')
                            No matches for “{{ $q }}”.
                        @else
                            No {{ strtolower($niche->term('customers')) }} yet. Add your first one.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $customers->links() }}
</div>
@endsection
