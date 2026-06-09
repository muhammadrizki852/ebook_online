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
        .preview-toolbar { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 14px 35px rgba(15, 23, 42, .08); padding: 18px; margin-bottom: 18px; }
        .excel-sheet { background: #fff; border: 1px solid #cbd5e1; box-shadow: 0 18px 45px rgba(15, 23, 42, .12); overflow: auto; }
        .excel-sheet table { margin: 0; min-width: 760px; }
        .excel-sheet th { background: #d9ead3; border: 1px solid #94a3b8; white-space: nowrap; }
        .excel-sheet td { border: 1px solid #cbd5e1; white-space: nowrap; }
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
    <div class="preview-shell">
        <div class="preview-toolbar d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <div class="text-uppercase text-success fw-bold small mb-1">Excel Preview</div>
                <h4 class="fw-bold mb-1">User Report</h4>
                <div class="text-muted small">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.users', request()->except(['download', 'page'])) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <button type="button" onclick="printClean()" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
                <a href="{{ route('admin.reports.users.export-excel', array_merge(request()->except(['download', 'page']), ['download' => 1])) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                </a>
            </div>
        </div>

        <div class="excel-sheet">
            <table class="table table-sm align-middle">
                <tr>
                    <td colspan="4" class="sheet-title text-center fw-bold">USER REPORT</td>
                </tr>
                <tr>
                    <td colspan="4" class="sheet-subtitle text-center">EBOOK STORE SYSTEM</td>
                </tr>
                <tr>
                    <td colspan="4" class="sheet-subtitle text-center">Period: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</td>
                </tr>
                <tr><td colspan="4"></td></tr>
                <tr>
                    <td colspan="2" class="fw-bold bg-light">Total Users</td>
                    <td>{{ $totalUsers }}</td>
                    <td class="fw-bold bg-light">Users Active in Period</td>
                    <td>{{ $newUsers }}</td>
                </tr>
                <tr><td colspan="4"></td></tr>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th colspan="2">Last Login</th>
                </tr>
                @forelse($userRecords as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td colspan="2">{{ ($user->last_login_at ?? $user->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No user login records found for this period.</td></tr>
                @endforelse
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
