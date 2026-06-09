<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; color: #0f172a; }
        .preview-shell { max-width: 1200px; margin: 0 auto; padding: 28px 16px; }
        .preview-toolbar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, .08);
            padding: 18px;
            margin-bottom: 18px;
        }
        .excel-sheet {
            background: #fff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .12);
            overflow: auto;
        }
        .excel-sheet table { margin: 0; min-width: 980px; }
        .excel-sheet th {
            background: #d9ead3;
            border: 1px solid #94a3b8;
            white-space: nowrap;
        }
        .excel-sheet td {
            border: 1px solid #cbd5e1;
            white-space: nowrap;
        }
        .sheet-title { background: #eef2ff; font-size: 18px; }
        .sheet-subtitle { background: #f8fafc; }
        .page-footer { display: none; }
        @page { size: A4; margin: 20mm; }
        html { counter-reset: page; }
        @media print {
            html, body { margin: 0; padding: 0; }
            body { background: #fff; }
            .preview-toolbar { display: none; }
            .preview-shell { max-width: none; padding: 0; }
            .excel-sheet { box-shadow: none; border: none; }
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
    <div class="preview-shell">
        <div class="preview-toolbar d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <div class="text-uppercase text-success fw-bold small mb-1">Excel Preview</div>
                <h4 class="fw-bold mb-1">Sales Report</h4>
                <div class="text-muted small">
                    {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    @if($search)
                        | Search: {{ $search }}
                    @endif
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.payments.report', request()->except('download')) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <button type="button" onclick="printClean()" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
                <a href="{{ route('admin.payments.export-excel', array_merge(request()->except('download'), ['download' => 1])) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                </a>
            </div>
        </div>

        <div class="excel-sheet">
            <table class="table table-sm align-middle">
                <tr>
                    <td colspan="9" class="sheet-title text-center fw-bold">SALES TRANSACTION REPORT</td>
                </tr>
                <tr>
                    <td colspan="9" class="sheet-subtitle text-center">EBOOK STORE SYSTEM</td>
                </tr>
                <tr>
                    <td colspan="9" class="sheet-subtitle text-center">
                        Period: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
                        @if($search)
                            | Search: {{ $search }}
                        @endif
                    </td>
                </tr>
                <tr><td colspan="9"></td></tr>
                <tr>
                    <td colspan="2" class="fw-bold bg-light">Total Successful Transactions</td>
                    <td>{{ $totalSales }}</td>
                    <td colspan="3" class="fw-bold bg-light">Total Revenue</td>
                    <td colspan="3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $purchase->midtrans_order_id ?? 'TRX-'.$purchase->id }}</td>
                        <td>{{ $purchase->user->name ?? 'Guest' }}</td>
                        <td>{{ $purchase->user->email ?? '-' }}</td>
                        <td>{{ $purchase->ebook->title ?? '-' }}</td>
                        <td>{{ $purchase->midtrans_payment_type ? \Illuminate\Support\Str::headline($purchase->midtrans_payment_type) : 'Manual Transfer' }}</td>
                        <td>{{ $purchase->paid_at ? $purchase->paid_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>Rp {{ number_format($purchase->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No transaction details found for this period.</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="8" class="fw-bold text-end bg-light">TOTAL</td>
                    <td class="fw-bold bg-light">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="page-footer"></div>
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
