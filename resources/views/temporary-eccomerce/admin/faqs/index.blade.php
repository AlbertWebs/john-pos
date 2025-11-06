@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manage FAQs</h1>
        <a href="{{ route('admin.faqs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add New FAQ
        </a>
    </div>



    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        @php
            $categories = ['general', 'ordering', 'shipping', 'returns', 'technical'];
            $categoryNames = [
                'general' => 'General',
                'ordering' => 'Ordering',
                'shipping' => 'Shipping',
                'returns' => 'Returns',
                'technical' => 'Technical'
            ];
            $categoryColors = [
                'general' => 'bg-blue-100 text-blue-600',
                'ordering' => 'bg-green-100 text-green-600',
                'shipping' => 'bg-yellow-100 text-yellow-600',
                'returns' => 'bg-red-100 text-red-600',
                'technical' => 'bg-purple-100 text-purple-600'
            ];
        @endphp
        
        @foreach($categories as $category)
            @php
                $count = $faqs->where('category', $category)->count();
                $activeCount = $faqs->where('category', $category)->where('is_active', true)->count();
            @endphp
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full {{ $categoryColors[$category] }}">
                        <i class="fas fa-question-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">{{ $categoryNames[$category] }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $count }}</p>
                        <p class="text-xs text-gray-500">{{ $activeCount }} active</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $faq->order }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $faq->question }}</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    {{ Str::limit($faq->answer, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($faq->category === 'general') bg-blue-100 text-blue-800
                                    @elseif($faq->category === 'ordering') bg-green-100 text-green-800
                                    @elseif($faq->category === 'shipping') bg-yellow-100 text-yellow-800
                                    @elseif($faq->category === 'returns') bg-red-100 text-red-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($faq->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($faq->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.faqs.toggle-status', $faq) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-toggle-{{ $faq->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No FAQs found. <a href="{{ route('admin.faqs.create') }}" class="text-blue-600 hover:text-blue-900">Create your first FAQ</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $faqs->links() }}
    </div>
</div>
@endsection 