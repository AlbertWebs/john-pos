@extends('layouts.app')

@section('title', 'Barcode Sheets Preview')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Barcode Sheets Preview</h1>
        <p class="text-gray-600 mt-1">Review the layout and then download A4 PDFs in batches.</p>
    </div>

    @if($totalLabels === 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 space-y-2">
        <p class="text-amber-800 font-medium">No barcode labels to print.</p>
        @if(isset($itemsWithBarcodeCount))
            @if($itemsWithBarcodeCount === 0)
                <p class="text-amber-700 text-sm">No items in your inventory have a barcode. Go to <a href="{{ route('barcodes.index') }}" class="underline font-medium">Items Without Barcodes</a>, select items, and use <strong>Generate All</strong> or generate barcodes for individual products.</p>
            @else
                <p class="text-amber-700 text-sm">You have <strong>{{ number_format($itemsWithBarcodeCount) }}</strong> item(s) with barcodes, but they are all excluded because their <strong>product name</strong> contains: <strong>{{ implode(', ', array_map('ucfirst', $noPrintTerms ?? [])) }}</strong>. Only product name is checked (not category). Check that items you want to print do not have one of these words in their name.</p>
            @endif
        @else
            <p class="text-amber-700 text-sm">Add items with barcodes and stock quantity, or check that items are not in excluded types{{ !empty($noPrintTerms ?? []) ? ' (' . implode(', ', array_map('ucfirst', $noPrintTerms ?? [])) . ')' : '' }}.</p>
        @endif
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <h2 class="text-lg font-semibold text-gray-800">Summary</h2>
        <ul class="space-y-2 text-gray-700">
            <li><strong>Total labels:</strong> {{ number_format($totalLabels) }}</li>
            <li><strong>Total A4 pages:</strong> {{ number_format($totalPages) }} ({{ $labelsPerPage }} labels per page)</li>
            <li><strong>PDF files to generate:</strong> {{ $totalPdfs }} (up to {{ $labelsPerPdf }} labels per PDF)</li>
        </ul>
        @if($totalPdfs > 0)
        <p class="text-sm text-gray-500">You will receive a single ZIP file containing {{ $totalPdfs }} PDF(s).</p>
        @endif
    </div>

    <div class="flex flex-wrap gap-3">
        @if($totalLabels > 0)
        <form action="{{ route('barcodes.downloadSheets') }}" method="post" class="inline">
            @csrf
            @if(!empty($hours))
                <input type="hidden" name="hours" value="{{ $hours }}" />
            @endif
            @foreach((array) $item_ids as $id)
                <input type="hidden" name="item_ids[]" value="{{ $id }}" />
            @endforeach
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Barcode Sheets (ZIP)
            </button>
        </form>
        @endif
        <a href="{{ route('barcodes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
            Back to Barcodes
        </a>
        <a href="{{ route('barcodes.products') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold transition">
            View Products With Barcodes
        </a>
    </div>
</div>
@endsection
