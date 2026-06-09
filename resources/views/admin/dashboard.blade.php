@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold text-uppercase" style="letter-spacing: 0.05em;">Total Users</p>
                        <h2 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-person me-1"></i>Registered users</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold text-uppercase" style="letter-spacing: 0.05em;">Ebooks Sold</p>
                        <h2 class="fw-bold mb-0">{{ number_format($totalSales) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: #fee2e2; color: #b91c1c;">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-check2-circle me-1"></i>Auto-approved purchases</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold text-uppercase" style="letter-spacing: 0.05em;">Ebooks in App</p>
                        <h2 class="fw-bold mb-0">{{ number_format($totalEbooks) }}</h2>
                    </div>
                    <div class="stat-icon" style="background: #dbeafe; color: #1d4ed8;">
                        <i class="bi bi-journal-richtext"></i>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-check-circle me-1"></i>{{ $publishedEbooks }} published, {{ $draftEbooks }} draft hidden</p>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold text-uppercase" style="letter-spacing: 0.05em;">Total Revenue</p>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                    <div class="stat-icon" style="background: #dcfce7; color: #15803d;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-graph-up me-1"></i>From completed payments</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions + Recent Purchases -->
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-lightning-charge me-2 text-primary"></i>Quick Actions</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Add New Ebook</div>
                            <small class="opacity-75">Upload PDF & cover image</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-success d-flex align-items-center gap-2">
                        <i class="bi bi-credit-card fs-5"></i>
                        <div class="text-start">
                            <div class="fw-semibold">View Payments</div>
                            <small class="opacity-75">Payments auto-approved</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                        <i class="bi bi-journal-richtext fs-5"></i>
                        <div class="text-start">
                            <div class="fw-semibold">Manage Ebooks</div>
                            <small class="opacity-75">{{ $totalEbooks }} in app</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-people fs-5"></i>
                        <div class="text-start">
                            <div class="fw-semibold">View Users</div>
                            <small class="opacity-75">{{ $totalUsers }} registered</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Purchases</h5>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>

                @if($recentPurchases->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">No purchases yet</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Ebook</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPurchases as $purchase)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold small">{{ $purchase->user->name ?? '-' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $purchase->user->email ?? '' }}</div>
                                        </td>
                                        <td class="small">{{ Str::limit($purchase->ebook->title ?? '-', 30) }}</td>
                                        <td class="fw-semibold text-success small">Rp {{ number_format($purchase->amount ?? $purchase->ebook?->price ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @if($purchase->payment_status === 'approved')
                                                <span class="badge badge-approved rounded-pill">Approved</span>
                                            @elseif($purchase->payment_status === 'pending')
                                                <span class="badge badge-pending rounded-pill">Pending</span>
                                            @else
                                                <span class="badge badge-rejected rounded-pill">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">{{ $purchase->created_at->format('M d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
