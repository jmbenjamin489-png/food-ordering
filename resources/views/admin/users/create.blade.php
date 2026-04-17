@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Create User</h2>
        <p class="text-sm text-gray-500">Add a new account</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm">Back to Users</a>
</div>

<div class="bg-white border border-gray-200 rounded-xl p-6">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users.partials.form', ['user' => null])
        <div class="mt-6 flex gap-3">
            <button class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-lg text-sm font-semibold">Create</button>
            <a href="{{ route('admin.users.index') }}" class="border border-gray-300 hover:border-blue-500 px-5 py-2 rounded-lg text-sm font-semibold">Cancel</a>
        </div>
    </form>
</div>
@endsection
