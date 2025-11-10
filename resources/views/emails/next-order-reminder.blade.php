@php
$summary = $summary ?? [];
$byStatus = $summary['by_status'] ?? [];
@endphp

<div style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h2 style="color: #1f2937; margin-bottom: 0.5rem;">Next Orders Reminder</h2>
    <p style="margin: 0 0 1rem;">
        The following customer requests are still awaiting restock. Please review and reorder as needed.
    </p>

    <div style="background: #f3f4f6; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        <p style="margin: 0; font-weight: 600;">
            Total pending items: {{ $summary['total'] ?? $orders->count() }}
        </p>
        @if(!empty($byStatus))
            <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                @foreach($byStatus as $status => $count)
                    <li>{{ ucfirst($status) }}: {{ $count }}</li>
                @endforeach
            </ul>
        @endif
        @if(!empty($summary['oldest_date']))
            <p style="margin: 0.5rem 0 0;">
                Oldest open request: {{ $summary['oldest_date'] }}
            </p>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #d1d5db;">Item</th>
                <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #d1d5db;">Customer</th>
                <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #d1d5db;">Status</th>
                <th style="text-align: left; padding: 0.75rem; border-bottom: 1px solid #d1d5db;">Requested</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                        <strong>{{ $order->item_name }}</strong>
                        <div style="font-size: 12px; color: #6b7280;">
                            Part #: {{ $order->part_number ?? 'N/A' }}<br>
                            Qty: {{ number_format($order->requested_quantity) }}
                            @if($order->notes)
                                <br>Notes: {{ $order->notes }}
                            @endif
                        </div>
                    </td>
                    <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                        {{ $order->customer_name ?? 'Walk-in' }}
                        @if($order->customer_contact)
                            <div style="font-size: 12px; color: #2563eb;">{{ $order->customer_contact }}</div>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                        {{ ucfirst($order->status) }}
                    </td>
                    <td style="padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                        {{ $order->created_at?->format('M j, Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 1.5rem; color: #6b7280;">
        This reminder was generated automatically. Update any completed items under Next Orders to keep this list current.
    </p>
</div>

