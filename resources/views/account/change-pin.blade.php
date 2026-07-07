@extends('layouts.app')

@section('title', 'Change Login PIN')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Change your login PIN</h1>
        <p class="text-gray-600 mt-1">Update the 4-digit PIN you use to sign in to the system.</p>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-6 text-sm text-amber-900">
        <p class="font-semibold">You will be signed out after saving</p>
        <p class="mt-1">For your security, you must log in again using your <strong>new PIN</strong> before you can continue working.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-6 pb-6 border-b border-gray-200">
            <p class="text-sm text-gray-500">Signed in as</p>
            <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-600">Username: {{ auth()->user()->username }}</p>
        </div>

        <form method="POST" action="{{ route('account.pin.update') }}" class="space-y-6" id="changePinForm">
            @csrf
            @method('PUT')

            <div>
                <label for="current_pin" class="block text-sm font-medium text-gray-700 mb-2">
                    Current PIN <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    name="current_pin"
                    id="current_pin"
                    value="{{ old('current_pin') }}"
                    maxlength="4"
                    minlength="4"
                    pattern="[0-9]{4}"
                    inputmode="numeric"
                    autocomplete="off"
                    required
                    autofocus
                    placeholder="Enter your current 4-digit PIN"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_pin') border-red-500 @enderror"
                >
                @error('current_pin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_pin" class="block text-sm font-medium text-gray-700 mb-2">
                    New PIN <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    name="new_pin"
                    id="new_pin"
                    value="{{ old('new_pin') }}"
                    maxlength="4"
                    minlength="4"
                    pattern="[0-9]{4}"
                    inputmode="numeric"
                    autocomplete="new-password"
                    required
                    placeholder="Choose a new 4-digit PIN"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('new_pin') border-red-500 @enderror"
                >
                @error('new_pin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Use 4 numbers only. Pick something you will remember but others cannot guess easily.</p>
            </div>

            <div>
                <label for="new_pin_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm new PIN <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    name="new_pin_confirmation"
                    id="new_pin_confirmation"
                    value="{{ old('new_pin_confirmation') }}"
                    maxlength="4"
                    minlength="4"
                    pattern="[0-9]{4}"
                    inputmode="numeric"
                    autocomplete="new-password"
                    required
                    placeholder="Enter the new PIN again"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg tracking-widest focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('new_pin_confirmation') border-red-500 @enderror"
                >
                @error('new_pin_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition"
                >
                    Save new PIN &amp; sign out
                </button>
                <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('#changePinForm input[inputmode="numeric"]').forEach((input) => {
            input.addEventListener('input', (event) => {
                event.target.value = event.target.value.replace(/\D/g, '').slice(0, 4);
            });
        });
    });
</script>
@endpush
