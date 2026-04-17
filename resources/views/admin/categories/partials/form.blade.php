<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <div class="md:col-span-8">
        <label class="block text-sm text-gray-600 mb-1">Category Name</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div class="md:col-span-4">
        <label class="block text-sm text-gray-600 mb-1">Sort Order</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div class="md:col-span-12">
        <label class="block text-sm text-gray-600 mb-1">Description</label>
        <textarea name="description" rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">{{ old('description', $category?->description) }}</textarea>
    </div>
    <div class="md:col-span-12">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   class="w-4 h-4"
                   {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Active</span>
        </label>
    </div>
</div>

@if($errors->any())
    <div class="mt-4 bg-red-50 border-2 border-red-200 text-red-700 p-3 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
