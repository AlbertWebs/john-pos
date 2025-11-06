@extends('layouts.app')

@section('title', 'SEO Settings')

@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">SEO Settings</h1>
        <button onclick="showAddModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Add New SEO Settings
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($seoSettings as $seo)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ isset($pageTypes[$seo->page_type]) ? $pageTypes[$seo->page_type] : $seo->page_type }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ \Illuminate\Support\Str::limit($seo->meta_title ?? 'Not set', 50) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ \Illuminate\Support\Str::limit($seo->meta_description ?? 'Not set', 80) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editSeoSettings({{ $seo->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <form action="{{ route('admin.seo-settings.destroy', $seo->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this SEO setting?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No SEO settings found. Click "Add New SEO Settings" to create one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
   

    <div id="seoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add SEO Settings</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="seoForm" method="POST">
                    @csrf
                    <div id="methodField"></div>

                    <div class="mb-4">
                        <label for="page_type" class="block text-sm font-medium text-gray-700 mb-2">Page Type *</label>
                        <select name="page_type" id="page_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Page Type</option>
                            @foreach($pageTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title (max 60 characters)</label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
                    </div>

                    <div class="mb-4">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description (max 160 characters)</label>
                        <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
                    </div>

                    <div class="mb-4">
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords (comma-separated)</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="auto spares, car parts, Wangige, vehicle parts">
                    </div>

                    <div class="mb-4">
                        <label for="og_title" class="block text-sm font-medium text-gray-700 mb-2">Open Graph Title</label>
                        <input type="text" name="og_title" id="og_title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="og_description" class="block text-sm font-medium text-gray-700 mb-2">Open Graph Description</label>
                        <textarea name="og_description" id="og_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="og_image" class="block text-sm font-medium text-gray-700 mb-2">Open Graph Image URL</label>
                        <input type="url" name="og_image" id="og_image" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://example.com/image.jpg">
                    </div>

                    <div class="mb-4">
                        <label for="structured_data" class="block text-sm font-medium text-gray-700 mb-2">Structured Data (JSON-LD)</label>
                        @verbatim
                        <textarea name="structured_data" id="structured_data" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder='{"@context": "https://schema.org", "@type": "LocalBusiness", "name": "My Business", "address": {...}}'></textarea>
                        @endverbatim
                        <p class="text-xs text-gray-500 mt-1">Enter valid JSON for structured data</p>
                    </div>

                    <div class="mb-4">
                        <label for="custom_meta_tags" class="block text-sm font-medium text-gray-700 mb-2">Custom Meta Tags (HTML)</label>
                        <textarea name="custom_meta_tags" id="custom_meta_tags" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm" placeholder='<meta name="robots" content="index, follow">'></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Add SEO Settings';
            document.getElementById('seoForm').action = '{{ route("admin.seo-settings.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('seoForm').reset();
            document.getElementById('page_type').disabled = false;
            document.getElementById('seoModal').classList.remove('hidden');
        }

        function editSeoSettings(id) {
            fetch('/admin/seo-settings/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Edit SEO Settings';
                    document.getElementById('seoForm').action = '/admin/seo-settings/' + id;
                    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    document.getElementById('page_type').value = data.page_type;
                    document.getElementById('page_type').disabled = true;
                    document.getElementById('meta_title').value = data.meta_title || '';
                    document.getElementById('meta_description').value = data.meta_description || '';
                    document.getElementById('meta_keywords').value = data.meta_keywords || '';
                    document.getElementById('og_title').value = data.og_title || '';
                    document.getElementById('og_description').value = data.og_description || '';
                    document.getElementById('og_image').value = data.og_image || '';
                    document.getElementById('structured_data').value = typeof data.structured_data === 'object' ? JSON.stringify(data.structured_data, null, 2) : (data.structured_data || '');
                    document.getElementById('custom_meta_tags').value = data.custom_meta_tags || '';
                    document.getElementById('seoModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading SEO settings');
                });
        }

        function closeModal() {
            document.getElementById('seoModal').classList.add('hidden');
        }
    </script>
</div>
@endsection
