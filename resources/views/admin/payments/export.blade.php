<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-family: 'Times New Roman', Times, serif; color: #000; padding: 20px; }
        .report-header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .report-title { font-size: 28px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .stat-box { border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: #f9f9f9; text-align: center; }
        .stat-label { font-size: 14px; color: #555; text-transform: uppercase; margin-bottom: 5px; }
        .stat-value { font-size: 20px; font-weight: bold; }
        .table { font-size: 13px; margin-top: 20px; }
        .table thead { background-color: #f2f2f2 !important; }
        .table th, .table td { border: 1px solid #dee2e6 !important; }
        .text-small { font-size: 12px; }
        .footer-note { margin-top: 50px; font-size: 12px; font-style: italic; color: #777; text-align: right; }
        .page-footer { display: none; }
        @page { size: A4; margin: 20mm; }
        html { counter-reset: page; }
        @media print {
            html, body { margin: 0; padding: 0; }
            body { background: #fff; }
            .no-print { display: none !important; }
            .table thead { -webkit-print-color-adjust: exact; background-color: #f2f2f2 !important; }
            .page-footer {
                display: block;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                padding: 0 16px 4px;
                text-align: right;
                font-size: 12px;
                color: #1f2937;
            }
            .page-footer::after {
                content: "Page " counter(page);
            }
        }
    </style>
</head>
<body>

    <div class="container no-print mb-4">
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Pratinjau laporan penjualan. Gunakan tombol di samping untuk menyimpan sebagai PDF.</span>
            <button onclick="printClean()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <div class="report-header">
        <div class="report-title">Sales Transaction Report</div>
        <div class="fs-5">EBOOK STORE SYSTEM</div>
        <div class="text-muted mt-2">
            Period: <strong>{{ $startDate->format('d F Y') }}</strong> - <strong>{{ $endDate->format('d F Y') }}</strong>
            @if($search)
                <br>Search: <strong>{{ $search }}</strong>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-6">
            <div class="stat-box">
                <div class="stat-label">Total Successful Transactions</div>
                <div class="stat-value text-primary">{{ $totalSales }} Sales</div>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-box">
                <div class="stat-label">Total Revenue (Gross)</div>
                <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">Transaction Details</h5>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr class="text-center">
                <th width="40">No</th>
                <th width="120">Date</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Ebook</th>
                <th width="100">Method</th>
                <th width="120">Paid At</th>
                <th width="130">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-bold">{{ $purchase->midtrans_order_id ?? 'TRX-'.$purchase->id }}</td>
                    <td>
                        <div class="fw-bold">{{ $purchase->user->name ?? 'Guest' }}</div>
                        <div class="text-muted text-small">{{ $purchase->user->email ?? '-' }}</div>
                    </td>
                    <td>{{ $purchase->ebook->title ?? '-' }}</td>
                    <td class="text-center">
                        {{ $purchase->midtrans_payment_type ? \Illuminate\Support\Str::headline($purchase->midtrans_payment_type) : 'Manual Transfer' }}
                    </td>
                    <td>{{ $purchase->paid_at ? $purchase->paid_at->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">No transaction details found for this period.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-dark">
                <th colspan="7" class="text-end pe-3">TOTAL</th>
                <th class="text-end">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer-note">
        Report generated on: {{ now()->format('d M Y, H:i') }} WIB<br>
        (c) {{ date('Y') }} Ebook Store System - Admin Panel
    </div>
    <div class="page-footer"></div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        const originalTitle = document.title;
        function printClean() {
            document.title = '';
            window.print();
        }
        window.addEventListener('afterprint', () => {
            document.title = originalTitle;
        });
    </script>
</body>
</html>
