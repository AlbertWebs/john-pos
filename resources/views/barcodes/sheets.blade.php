<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barcode Sheets</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
        }
        
        @page {
            size: A4;
            margin: 10mm;
        }
        
        .barcode-page {
            width: 100%;
            page-break-after: always;
        }
        
        .barcode-page:last-child {
            page-break-after: auto;
        }
        
        table.barcode-grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        table.barcode-grid td {
            width: 25%;
            height: 38mm;
            padding: 2mm;
            text-align: center;
            vertical-align: top;
            border: 1px solid #e5e5e5;
        }
        
        table.barcode-grid img.barcode-img {
            max-width: 100%;
            max-height: 22mm;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        
        .item-code {
            font-size: 7pt;
            font-weight: bold;
            margin-top: 1mm;
            word-break: break-all;
            line-height: 1.2;
        }
        
        .item-name {
            font-size: 6pt;
            color: #333;
            margin-top: 0.5mm;
            line-height: 1.15;
        }
        
        @media print {
            body { padding: 0; margin: 0; }
            .barcode-page { page-break-after: always; }
            .barcode-page:last-child { page-break-after: auto; }
        }
    </style>
</head>
<body>
    @php
        $labelsPerPage = 28; /* 4 columns x 7 rows */
        $pages = array_chunk($labels, $labelsPerPage);
    @endphp
    @foreach($pages as $pageLabels)
    <div class="barcode-page">
        <table class="barcode-grid" cellpadding="0" cellspacing="0">
            @foreach(array_chunk($pageLabels, 4) as $row)
            <tr>
                @foreach($row as $label)
                <td>
                    @if(!empty($label['barcode_image_base64']))
                        <img src="{{ $label['barcode_image_base64'] }}" alt="" class="barcode-img" />
                    @else
                        <span style="font-size: 8pt;">{{ $label['barcode'] ?? '—' }}</span>
                    @endif
                    <div class="item-code">{{ $label['barcode'] ?? '—' }}</div>
                    <div class="item-name">{{ \Illuminate\Support\Str::limit($label['name'] ?? '', 25) }}</div>
                </td>
                @endforeach
                @if(count($row) < 4)
                    @for($i = count($row); $i < 4; $i++)
                    <td></td>
                    @endfor
                @endif
            </tr>
            @endforeach
        </table>
    </div>
    @endforeach
</body>
</html>
