@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Payments</h1>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Midtrans Sandbox</span>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 text-start" width="120">Action</th>
                        <th>User</th>
                        <th>Ebook</th>
                        <th>Amount</th>
                        <th>Proof / Notes</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="ps-4 text-start">
                                <button class="btn btn-sm btn-outline-info" onclick="showInvoice('{{ route('admin.payments.invoice', $purchase) }}')">
                                    <i class="bi bi-receipt me-1"></i>Invoice
                                </button>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $purchase->user->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $purchase->user->email ?? '-' }}</div>
                            </td>
                            <td>{{ $purchase->ebook->title ?? '-' }}</td>
                            <td class="fw-semibold text-success">Rp {{ number_format($purchase->amount ?? $purchase->ebook?->price ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if($purchase->midtrans_order_id)
                                    <div class="small fw-semibold">{{ $purchase->midtrans_order_id }}</div>
                                    <div class="small text-muted">{{ $purchase->midtrans_payment_type ? Str::headline($purchase->midtrans_payment_type) : 'Midtrans Sandbox' }}</div>
                                @elseif($purchase->payment_proof)
                                    <a href="{{ asset('storage/' . $purchase->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bi bi-receipt me-1"></i>View Proof
                                    </a>
                                @else
                                    <div class="text-muted small mb-1">No proof uploaded</div>
                                @endif
                                @if($purchase->notes)
                                    <div class="small text-muted">{{ Str::limit($purchase->notes, 70) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($purchase->payment_status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($purchase->payment_status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $purchase->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $purchases->withQueryString()->links() }}</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">Detail Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light">
                <iframe id="invoiceFrame" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    const invoiceFrame = document.getElementById('invoiceFrame');

    function showInvoice(url) {
        const finalUrl = url + (url.includes('?') ? '&' : '?') + 'hide_nav=1';
        invoiceFrame.src = finalUrl;
        invoiceModal.show();
    }
    
    document.getElementById('invoiceModal').addEventListener('hidden.bs.modal', function () {
        invoiceFrame.src = '';
    });
</script>
@endsection
