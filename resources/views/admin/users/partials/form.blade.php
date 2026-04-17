<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm text-gray-600 mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $user?->name) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Password {{ $user ? '(leave blank to keep current)' : '' }}</label>
        <input type="password" name="password" {{ $user ? '' : 'required' }}
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Role</label>
        <select name="role" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
            <option value="customer" {{ old('role', $user?->role) === 'customer' ? 'selected' : '' }}>Customer</option>
            <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div>
        <label class="block text-sm text-gray-600 mb-1">Address</label>
        <input type="text" name="address" value="{{ old('address', $user?->address) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-gray-900 bg-white">
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   class="w-4 h-4"
                   {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Active account</span>
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
