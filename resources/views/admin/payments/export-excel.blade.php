<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sales Report Excel</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #999; padding: 6px; }
        th { background: #d9ead3; font-weight: bold; }
        .title { font-size: 18px; font-weight: bold; text-align: center; }
        .subtitle { text-align: center; }
        .total { background: #f3f4f6; font-weight: bold; }
        .text { mso-number-format:"\@"; }
        .number { mso-number-format:"0"; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="9" class="title">SALES TRANSACTION REPORT</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">EBOOK STORE SYSTEM</td>
        </tr>
        <tr>
            <td colspan="9" class="subtitle">
                Period: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
                @if($search)
                    | Search: {{ $search }}
                @endif
            </td>
        </tr>
        <tr><td colspan="9"></td></tr>
        <tr>
            <td colspan="2" class="total">Total Successful Transactions</td>
            <td class="number">{{ $totalSales }}</td>
            <td colspan="3" class="total">Total Revenue</td>
            <td colspan="3" class="number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="9"></td></tr>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Ebook</th>
            <th>Method</th>
            <th>Paid At</th>
            <th>Amount</th>
        </tr>
        @forelse($purchases as $purchase)
            <tr>
                <td class="number">{{ $loop->iteration }}</td>
                <td class="text">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                <td class="text">{{ $purchase->midtrans_order_id ?? 'TRX-'.$purchase->id }}</td>
                <td>{{ $purchase->user->name ?? 'Guest' }}</td>
                <td>{{ $purchase->user->email ?? '-' }}</td>
                <td>{{ $purchase->ebook->title ?? '-' }}</td>
                <td>{{ $purchase->midtrans_payment_type ? \Illuminate\Support\Str::headline($purchase->midtrans_payment_type) : 'Manual Transfer' }}</td>
                <td class="text">{{ $purchase->paid_at ? $purchase->paid_at->format('d/m/Y H:i') : '-' }}</td>
                <td class="number">{{ (int) $purchase->amount }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9">No transaction details found for this period.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="8" class="total">TOTAL</td>
            <td class="total number">{{ (int) $totalRevenue }}</td>
        </tr>
    </table>
</body>
</html>
