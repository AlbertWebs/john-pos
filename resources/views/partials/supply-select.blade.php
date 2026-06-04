@props(['supplies', 'selected' => null, 'required' => false])

<div>
    <label for="supply_id" class="block text-sm font-medium text-gray-700 mb-2">
        Supply / Supplier
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    <select
        name="supply_id"
        id="supply_id"
        @if($required) required @endif
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('supply_id') border-red-500 @enderror"
    >
        <option value="">Select supply (optional)</option>
        @foreach($supplies as $supply)
            <option value="{{ $supply->id }}" @selected(old('supply_id', $selected) == $supply->id)>
                {{ $supply->name }}@if($supply->contact_person) — {{ $supply->contact_person }}@endif
            </option>
        @endforeach
    </select>
    @error('supply_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    <p class="mt-1 text-xs text-gray-500">
        <a href="{{ route('supplies.create', ['redirect' => url()->current()]) }}" class="text-blue-600 hover:text-blue-800">+ Add new supply</a>
        @if($supplies->isEmpty())
            — no supplies yet, create one first.
        @endif
    </p>
</div>
