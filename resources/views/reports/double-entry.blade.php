@extends('layouts.app')

@section('title', 'Double Entry Report')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Double Entry Report</h1>
        <p class="text-gray-600 mt-1">Journal entries with matching debits and credits for the selected period.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('reports.double-entry') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Apply</button>
                <a href="{{ route('reports.double-entry') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold">Reset</a>
                <a href="{{ route('reports.double-entry', array_merge(request()->query(), ['export' => 'excel'])) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Excel</a>
                <a href="{{ route('reports.double-entry', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">PDF</a>
            </div>
        </form>
        <p class="text-sm text-gray-500 mt-3">
            Includes <strong>sales</strong> (cash/revenue and COGS/inventory), <strong>stock received</strong>, and <strong>returns</strong>.
            Each journal groups balanced debit and credit lines.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Journal entries</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($entryCount) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Total debits</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">KES {{ number_format($totals['total_debits'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Total credits</p>
            <p class="text-2xl font-bold text-green-700 mt-1">KES {{ number_format($totals['total_credits'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-5">
            <p class="text-sm text-gray-500">Books balanced</p>
            <p class="text-2xl font-bold mt-1 {{ $totals['balanced'] ? 'text-emerald-700' : 'text-red-600' }}">
                {{ $totals['balanced'] ? 'Yes' : 'No' }}
            </p>
            @if(!$totals['balanced'])
            <p class="text-xs text-red-600 mt-1">Difference: KES {{ number_format($totals['difference'], 2) }}</p>
            @endif
        </div>
    </div>

    @if($trialBalance->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Trial balance (by account)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Account</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Debits</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Credits</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Net (Dr − Cr)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($trialBalance as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $account->account }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($account->debits, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ number_format($account->credits, 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums {{ $account->net >= 0 ? 'text-blue-700' : 'text-red-600' }}">{{ number_format($account->net, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-4 py-3">Total</td>
                        <td class="px-4 py-3 text-right tabular-nums">KES {{ number_format($totals['total_debits'], 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">KES {{ number_format($totals['total_credits'], 2) }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">KES {{ number_format($totals['difference'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Journal detail</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">#</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Type</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Reference</th>
                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Account</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Debit</th>
                        <th class="px-3 py-3 text-right font-semibold text-gray-700">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr class="hover:bg-gray-50 {{ $row['is_first_line'] ? 'border-t-2 border-gray-200' : '' }}">
                        <td class="px-3 py-2 text-gray-500">
                            @if($row['is_first_line'])
                                {{ $row['journal_no'] }}
                                @if(!$row['entry_balanced'])
                                    <span class="text-red-500" title="Entry out of balance">⚠</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">
                            @if($row['is_first_line'])
                                {{ $row['date']->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-500">{{ $row['date']->format('h:i A') }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-700">
                            @if($row['is_first_line']){{ $row['type'] }}@endif
                        </td>
                        <td class="px-3 py-2 text-gray-900">
                            @if($row['is_first_line'])
                                @if($row['url'])
                                    <a href="{{ $row['url'] }}" class="text-blue-600 hover:text-blue-800">{{ $row['reference'] }}</a>
                                @else
                                    {{ $row['reference'] }}
                                @endif
                            @endif
                        </td>
                        <td class="px-3 py-2 font-medium text-gray-900">{{ $row['account'] }}</td>
                        <td class="px-3 py-2 text-right tabular-nums text-blue-700">
                            @if($row['side'] === 'debit') KES {{ number_format($row['amount'], 2) }} @else — @endif
                        </td>
                        <td class="px-3 py-2 text-right tabular-nums text-green-700">
                            @if($row['side'] === 'credit') KES {{ number_format($row['amount'], 2) }} @else — @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">No journal entries in this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
