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
        }
        
        .sticker {
            width: 63.5mm; /* Standard label size (2.5 inches) */
            height: 38.1mm; /* Standard label size (1.5 inches) */
            border: 1px solid #000;
            padding: 3mm;
            display: inline-block;
            margin: 2mm;
            page-break-inside: avoid;
            vertical-align: top;
            box-sizing: border-box;
        }
        
        .sticker-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }
        
        .sticker-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }
        
        .item-name {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 1mm;
            line-height: 1.2;
            word-wrap: break-word;
            max-height: 8mm;
            overflow: hidden;
        }
        
        .part-number {
            font-size: 7px;
            color: #333;
        }
        
        .barcode-section {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
            font-size: 9px;
            margin-top: 1mm;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        /* Barcode visual representation using bars */
        .barcode-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 15mm;
            margin: 2mm 0;
        }
        
        .barcode-bar {
            display: inline-block;
            background: #000;
            height: 12mm;
            margin: 0 0.5mm;
        }
        
        .bar-thin { width: 1mm; }
        .bar-medium { width: 2mm; }
        .bar-thick { width: 3mm; }
        
        .sticker-footer {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 2mm;
            margin-top: 2mm;
            font-size: 7px;
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
            }
            
            .sticker {
                margin: 0;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    @foreach($items as $item)
    <div class="sticker">
        <div class="sticker-content">
            <div class="sticker-header">
                <div class="item-name">{{ Str::limit($item->name, 30) }}</div>
                <div class="part-number">{{ $item->part_number }}</div>
            </div>
            
            <div class="barcode-section">
                <div class="barcode-visual">
                    @php
                        // Simple barcode visual representation
                        $barcode = $item->barcode;
                        $chars = str_split($barcode);
                        $barPattern = [];
                        foreach ($chars as $char) {
                            $ascii = ord($char);
                            // Create pattern based on character
                            if ($ascii % 3 == 0) {
                                $barPattern[] = 'bar-thin';
                            } elseif ($ascii % 3 == 1) {
                                $barPattern[] = 'bar-medium';
                            } else {
                                $barPattern[] = 'bar-thick';
                            }
                        }
                    @endphp
                    @foreach($barPattern as $barClass)
                        <span class="barcode-bar {{ $barClass }}"></span>
                    @endforeach
                </div>
                <div class="barcode-number">{{ $item->barcode }}</div>
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
