@extends('layouts.admin')

@section('title', 'Menu Management')

@section('page-title', 'Menu Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-4">
        <input type="text" id="searchMenu" placeholder="Search menu items..."
               class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
               onkeyup="filterMenu()">
    </div>
    <a href="{{ route('admin.menu.create') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
        + Add New Item
    </a>
</div>

<div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden">
    <table class="w-full" id="menuTable">
        <thead class="bg-gray-50">
            <tr class="border-b-2 border-gray-200">
                <th class="text-left py-4 px-6 text-dark font-bold">Image</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Name</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Category</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Price</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Status</th>
                <th class="text-left py-4 px-6 text-dark font-bold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menuItems ?? [] as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-50">
                <td class="py-4 px-6">
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded">
                    @else
                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-400 text-xs">No Image</span>
                    </div>
                    @endif
                </td>
                <td class="py-4 px-6">
                    <p class="font-semibold text-dark">{{ $item->name }}</p>
                    <p class="text-sm text-gray-600">{{ Str::limit($item->description, 40) }}</p>
                </td>
                <td class="py-4 px-6 text-dark">{{ $item->category->name ?? 'Uncategorized' }}</td>
                <td class="py-4 px-6 font-semibold text-primary">${{ number_format($item->price, 2) }}</td>
                <td class="py-4 px-6">
                    <form action="{{ route('admin.menu.toggle', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </button>
                    </form>
                </td>
                <td class="py-4 px-6">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.menu.edit', $item) }}" class="text-primary hover:underline">Edit</a>
                        <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">No menu items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($menuItems) && $menuItems->hasPages())
<div class="mt-6">
    {{ $menuItems->links() }}
</div>
@endif

<script>
function filterMenu() {
    let input = document.getElementById('searchMenu').value.toLowerCase();
    let table = document.getElementById('menuTable');
    let trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) {
        let nameTd = trs[i].getElementsByTagName('td')[1];
        if (nameTd) {
            let txtValue = nameTd.textContent || nameTd.innerText;
            trs[i].style.display = txtValue.toLowerCase().includes(input) ? '' : 'none';
        }
    }
}
</script>
@endsection
