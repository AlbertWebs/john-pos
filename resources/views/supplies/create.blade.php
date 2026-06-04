@extends('layouts.app')

@section('title', 'Add Supply')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ request('redirect') ? request('redirect') : route('supplies.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Add Supply</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('supplies.store') }}">
            @csrf
            @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif

            @include('supplies._form')

            <div class="flex justify-end gap-4 pt-6 mt-6 border-t">
                <a href="{{ request('redirect') ? request('redirect') : route('supplies.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Create Supply</button>
            </div>
        </form>
    </div>
</div>
@endsection
