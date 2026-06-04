<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Supply name <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $supply->name ?? '') }}" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
            placeholder="e.g., ABC Auto Parts Ltd">
        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact person</label>
            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supply->contact_person ?? '') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $supply->phone ?? '') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $supply->email ?? '') }}"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
        <textarea name="address" id="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $supply->address ?? '') }}</textarea>
    </div>
    <div>
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
        <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes', $supply->notes ?? '') }}</textarea>
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="active" @selected(old('status', $supply->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $supply->status ?? '') === 'inactive')>Inactive</option>
        </select>
    </div>
</div>
