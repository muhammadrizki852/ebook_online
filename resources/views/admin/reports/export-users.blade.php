<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: white; font-family: 'Times New Roman', Times, serif; color: #000; padding: 20px; }
        .report-header { text-align: center; margin-bottom: 32px; border-bottom: 2px solid #000; padding-bottom: 18px; }
        .report-title { font-size: 28px; font-weight: bold; text-transform: uppercase; margin-bottom: 6px; }
        .stat-box { border: 1px solid #ddd; padding: 14px; border-radius: 8px; background: #f9f9f9; text-align: center; }
        .stat-label { font-size: 13px; color: #555; text-transform: uppercase; margin-bottom: 4px; }
        .stat-value { font-size: 20px; font-weight: bold; }
        .table { font-size: 13px; margin-top: 18px; }
        .table thead { background-color: #f2f2f2 !important; }
        .table th, .table td { border: 1px solid #dee2e6 !important; }
        .footer-note { margin-top: 40px; font-size: 12px; font-style: italic; color: #777; text-align: right; }
        .page-footer { display: none; }
        @page { size: A4; margin: 20mm; }
        html { counter-reset: page; }
        @media print {
            html, body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            body { background: #fff; }
            .page-footer {
                display: block !important;
                position: fixed !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                padding: 0 20mm 4mm !important;
                text-align: right !important;
                font-size: 12px !important;
                color: #1f2937 !important;
                z-index: 9999 !important;
                pointer-events: none !important;
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
            <span>Pratinjau laporan pengguna. Gunakan tombol di samping untuk menyimpan sebagai PDF.</span>
            <button onclick="printClean()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <div class="report-header">
        <div class="report-title">User Report</div>
        <div class="fs-5">EBOOK STORE SYSTEM</div>
        <div class="text-muted mt-2">Period: <strong>{{ $startDate->format('d F Y') }}</strong> - <strong>{{ $endDate->format('d F Y') }}</strong></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-box">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-box">
                <div class="stat-label">Users Active in Period</div>
                <div class="stat-value">{{ number_format($newUsers) }}</div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3">User Login History</h5>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr class="text-center">
                <th width="40">No</th>
                <th>Name</th>
                <th>Email</th>
                <th width="180">Last Login</th>
            </tr>
        </thead>
        <tbody>
            @forelse($userRecords as $user)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ($user->last_login_at ?? $user->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">No user login records found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">Report generated on: {{ now()->format('d M Y, H:i') }}</div>
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
