@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Categories</h2>
        <p class="text-sm text-gray-500">Organize your menu items</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold">
        + Add Category
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-4 mb-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <div class="md:col-span-9">
            <label class="block text-sm text-gray-600 mb-1">Search</label>
            <input type="text" name="search"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                   placeholder="Search category" value="{{ request('search') }}">
        </div>
        <div class="md:col-span-3 flex items-end">
            <button class="w-full border border-gray-300 hover:border-blue-500 px-4 py-2 rounded-lg text-sm font-semibold">
                Filter
            </button>
        </div>
    </form>
</div>

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Description</th>
                    <th class="py-3 px-4">Sort</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-slate-800">{{ $category->name }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ \Illuminate\Support\Str::limit($category->description, 60) }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $category->sort_order }}</td>
                        <td class="py-3 px-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection
