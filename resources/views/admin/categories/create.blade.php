@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Create Category</h2>
        <p class="text-sm text-gray-500">Add a new category</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline text-sm">Back to Categories</a>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-6">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        @include('admin.categories.partials.form', ['category' => null])
        <div class="mt-6 flex gap-3">
            <button class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-lg text-sm font-semibold">Create</button>
            <a href="{{ route('admin.categories.index') }}" class="border border-gray-300 hover:border-blue-500 px-5 py-2 rounded-lg text-sm font-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection
