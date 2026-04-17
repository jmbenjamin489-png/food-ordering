@extends('layouts.admin')

@section('title', 'Menu Management')
@section('page-title', 'Menu Management')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="mb-6 flex justify-between items-center">
    <input type="text"
           placeholder="Search menu items..."
           class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">

    <a href="{{ route('admin.menu.create') }}"
       class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
        + Add New Item
    </a>
</div>

<div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full border-collapse">
        <thead class="bg-gray-50">
            <tr class="border-b-2 border-gray-200">
                <th class="text-left py-4 px-6 font-bold">Image</th>
                <th class="text-left py-4 px-6 font-bold">Name</th>
                <th class="text-left py-4 px-6 font-bold">Category</th>
                <th class="text-left py-4 px-6 font-bold">Price</th>
                <th class="text-left py-4 px-6 font-bold">Status</th>
                <th class="text-left py-4 px-6 font-bold">Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse($menuItems as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                {{-- IMAGE --}}
                <td class="py-4 px-6">
                    @if($item->image)
                        <img src="{{ asset($item->image) }}"
                             class="w-16 h-16 object-cover rounded">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">
                            No Image
                        </div>
                    @endif
                </td>

                {{-- NAME --}}
                <td class="py-4 px-6">
                    <p class="font-semibold">{{ $item->name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ \Illuminate\Support\Str::limit($item->description, 40) }}
                    </p>
                </td>

                {{-- CATEGORY --}}
                <td class="py-4 px-6">
                    {{ $item->category->name ?? 'Uncategorized' }}
                </td>

                {{-- PRICE --}}
                <td class="py-4 px-6 font-semibold text-primary">
                    ${{ number_format($item->price, 2) }}
                </td>

                {{-- STATUS --}}
                <td class="py-4 px-6">
                    <form action="{{ route('admin.menu.toggle', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </button>
                    </form>
                </td>

                {{-- ACTIONS --}}
                <td class="py-4 px-6">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.menu.edit', $item->id) }}"
                           class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('admin.menu.destroy', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this menu item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="py-10 text-center text-gray-500">
                    No menu items found
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($menuItems->hasPages())
    <div class="mt-6">
        {{ $menuItems->links() }}
    </div>
@endif

@endsection
