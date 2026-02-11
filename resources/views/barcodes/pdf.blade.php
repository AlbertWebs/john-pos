<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Stickers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            padding: 5mm;
            margin: 0;
            line-height: 1;
        }
        
        .sticker {
            width: 63.5mm; /* Standard label size (2.5 inches) */
            height: 38.1mm; /* Standard label size (1.5 inches) */
            border: 1px solid #000;
            padding: 2mm;
            display: inline-block;
            margin: 2mm;
            margin-bottom: 3mm;
            page-break-inside: avoid;
            page-break-after: auto;
            vertical-align: top;
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
        }
        
        .sticker-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
            overflow: hidden;
        }
        
        .sticker-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 1.5mm;
            margin-bottom: 1.5mm;
            flex-shrink: 0;
            min-height: 0;
        }
        
        .item-name {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 0.5mm;
            line-height: 1.1;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            max-height: 6mm;
        }
        
        .part-number {
            font-size: 7px;
            font-weight: bold;
            color: #000;
            margin-top: 0.5mm;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .part-number-label {
            font-size: 6px;
            color: #666;
            margin-right: 2px;
        }
        
        .stock-quantity {
            font-size: 7px;
            font-weight: bold;
            color: #000;
            margin-top: 0.5mm;
            padding: 0.5mm 2mm;
            background: #f0f0f0;
            border: 1px solid #000;
            display: inline-block;
        }
        
        .barcode-section {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 0;
            overflow: hidden;
        }
        
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 2mm 0;
            line-height: 1.2;
            border: 2px solid #000;
            padding: 2mm;
            background: #fff;
        }
        
        .barcode-number {
            font-size: 8px;
            margin-top: 0.5mm;
            font-weight: bold;
            letter-spacing: 0.5px;
            word-break: break-all;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
        
        /* Barcode image */
        .barcode-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 12mm;
            margin: 1mm 0;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .barcode-image {
            max-width: 100%;
            max-height: 12mm;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        
        .sticker-footer {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 1mm;
            margin-top: 1mm;
            font-size: 6px;
            flex-shrink: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .price {
            font-weight: bold;
            font-size: 8px;
        }
        
        @page {
            size: A4;
            margin: 5mm;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .sticker {
                margin: 2mm;
                margin-bottom: 3mm;
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @foreach($items as $item)
    <div class="sticker">
        <div class="sticker-content">
            <div class="sticker-header">
                <div class="item-name">{{ Str::limit($item->name, 25) }}</div>
                @if($item->part_number)
                <div class="part-number">
                    <span class="part-number-label">Part #:</span>{{ Str::limit($item->part_number, 20) }}
                </div>
                @endif
            </div>
            
            <div class="barcode-section">
                <div class="barcode-visual">
                    @if(isset($item->barcode_image_base64) && $item->barcode_image_base64)
                        <img src="{{ $item->barcode_image_base64 }}" alt="Barcode {{ $item->barcode }}" class="barcode-image" />
                    @else
                        <div style="font-size: 6px; color: #999;">{{ $item->barcode }}</div>
                    @endif
                </div>
                <div class="barcode-number">{{ Str::limit($item->barcode, 25) }}</div>
                @if($item->part_number)
                <div class="part-number" style="margin-top: 1mm; font-size: 7px;">
                    <span class="part-number-label">PN:</span>{{ Str::limit($item->part_number, 18) }}
                </div>
                @endif
            </div>
            
            <div class="sticker-footer">
                @if($item->category)
                <div>{{ $item->category->name }}</div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>
