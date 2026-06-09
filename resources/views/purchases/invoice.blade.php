<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; padding: 40px 0; }
        .invoice-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 50px; max-width: 850px; margin: auto; border: 1px solid #e2e8f0; }
        .brand-logo { color: #4f46e5; font-weight: 800; font-size: 24px; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .invoice-title { font-size: 32px; font-weight: 800; color: #1e293b; margin-top: 10px; }
        .status-badge { padding: 6px 16px; border-radius: 999px; font-weight: 700; font-size: 14px; text-transform: uppercase; }
        .status-approved { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef3c7; color: #b45309; }
        .status-rejected { background: #fee2e2; color: #b91c1c; }
        .info-label { color: #64748b; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .info-value { color: #1e293b; font-weight: 700; font-size: 16px; }
        .table-invoice thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
        .table-invoice th { color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 15px; }
        .table-invoice td { padding: 20px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        .item-name { font-weight: 700; color: #1e293b; font-size: 15px; }
        .item-category { color: #64748b; font-size: 12px; }
        .total-section { background: #f8fafc; border-radius: 12px; padding: 25px; margin-top: 30px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total-row:last-child { margin-bottom: 0; padding-top: 15px; border-top: 2px dashed #cbd5e1; margin-top: 15px; }
        .total-label { color: #64748b; font-weight: 600; }
        .total-value { color: #1e293b; font-weight: 700; }
        .grand-total { font-size: 24px; color: #4f46e5; }
        .footer-note { text-align: center; color: #94a3b8; font-size: 13px; margin-top: 40px; }
        .print-btn { position: fixed; bottom: 30px; right: 30px; border-radius: 999px; padding: 12px 25px; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); font-weight: 700; }
        
        @media print {
            @page { size: A4 portrait; margin: 0; }
            * { box-sizing: border-box; }
            html, body { width: 100%; background: white; padding: 0; margin: 0; overflow: visible; }
            .invoice-card { box-shadow: none; border: none; max-width: 100%; width: 100%; padding: 12mm; margin: 0; }
            .invoice-card .row { margin-left: 0; margin-right: 0; }
            .invoice-card .row > * { padding-left: 0; padding-right: 0; }
            .print-btn, .back-btn { display: none !important; }
        }
    </style>
</head>
<body>

    @if(!request()->has('hide_nav'))
    <div class="container mb-4 d-flex justify-content-between align-items-center no-print">
        <a href="{{ route('home', ['page' => 'rak']) }}" class="btn btn-outline-secondary back-btn border-0 fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Perpustakaan
        </a>
    </div>
    @endif

    <div class="invoice-card">
        <div class="row align-items-start">
            <div class="col-sm-6">
                <div class="brand-logo mb-4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19.5C4 18.837 4.26339 18.2011 4.73223 17.7322C5.20107 17.2634 5.83696 17 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.5 2H20V22H6.5C5.83696 22 5.20107 21.7366 4.73223 21.2678C4.26339 20.7989 4 20.163 4 19.5V4.5C4 3.83696 4.26339 3.20107 4.73223 2.73223C5.20107 2.26339 5.83696 2 6.5 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    EBOOK STORE
                </div>
                <div class="invoice-title">INVOICE</div>
                <div class="text-muted mt-1">#{{ $transactionId }}</div>
            </div>
            <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                <span class="status-badge status-{{ $purchase->payment_status }}">
                    {{ $purchase->payment_status }}
                </span>
                <div class="mt-4">
                    <div class="info-label">Tanggal Transaksi</div>
                    <div class="info-value">{{ $purchase->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4 border-top">
            <div class="col-sm-4">
                <div class="info-label">Dibayar Oleh</div>
                <div class="info-value">{{ $purchase->user->name }}</div>
                <div class="text-muted small">{{ $purchase->user->email }}</div>
            </div>
            <div class="col-sm-4 mt-4 mt-sm-0">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">{{ $paymentMethod }}</div>
                <div class="text-muted small">Status: {{ Str::headline($purchase->midtrans_transaction_status ?? 'Success') }}</div>
            </div>
            <div class="col-sm-4 mt-4 mt-sm-0 text-sm-end">
                <div class="info-label">Waktu Pembayaran</div>
                <div class="info-value">{{ $paidAt ? $paidAt->format('d M Y, H:i') . ' WIB' : '-' }}</div>
            </div>
        </div>

        <div class="table-responsive mt-5">
            <table class="table table-invoice">
                <thead>
                    <tr>
                        <th>Item E-Book</th>
                        <th class="text-center">Kuantitas</th>
                        <th class="text-end">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="item-name">{{ $purchase->ebook->title }}</div>
                            <div class="item-category">{{ $purchase->ebook->category }}</div>
                        </td>
                        <td class="text-center">1</td>
                        <td class="text-end fw-bold">Rp {{ number_format($purchase->ebook->price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Subtotal</span>
                        <span class="total-value">Rp {{ number_format($purchase->ebook->price, 0, ',', '.') }}</span>
                    </div>
                    @if($purchase->midtrans_order_id && $purchase->amount > $purchase->ebook->price)
                    <div class="total-row">
                        <span class="total-label">Biaya Layanan</span>
                        <span class="total-value">Rp {{ number_format($purchase->amount - $purchase->ebook->price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="total-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-value grand-total">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-note">
            <p class="mb-1">Terima kasih telah berbelanja di <strong>Ebook Store</strong>.</p>
            <p>Ini adalah bukti pembayaran sah yang dihasilkan secara otomatis.</p>
        </div>
    </div>

    <button onclick="window.print()" class="btn btn-primary print-btn no-print">
        <i class="bi bi-printer-fill me-2"></i>Cetak / Simpan PDF
    </button>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>
