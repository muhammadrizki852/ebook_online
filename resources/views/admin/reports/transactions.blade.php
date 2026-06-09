@extends('layouts.admin')

@section('title', 'Transaction Report')
@section('page-title', 'Transaction Report')

@section('styles')
<style>
    .report-card { border: 0; border-radius: 16px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); }
    .report-icon { width: 52px; height: 52px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    .transaction-chart-card { overflow: hidden; }
    .transaction-chart-card .card-header { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); }
    .transaction-chart-shell { height: 360px; position: relative; }
    .chart-summary-badge { border-radius: 999px; padding: 0.45rem 0.75rem; background: #eef2ff; color: #4338ca; font-size: 0.78rem; font-weight: 700; }
    .chart-empty-state { min-height: 240px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-weight: 600; }
    .activity-table-card { overflow: hidden; }
    .activity-table-card .card-header { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); }
    .activity-table { min-width: 1040px; }
    .activity-table > :not(caption) > * > * { padding: 0.82rem 0.9rem; }
    .activity-table thead th { color: #64748b; font-size: 0.72rem; letter-spacing: 0; text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid #e2e8f0; }
    .activity-table tbody tr { transition: background-color 0.15s ease; }
    .activity-table tbody td { vertical-align: middle; border-color: #edf2f7; }
    .activity-view-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; }
    .activity-no { width: 44px; color: #64748b; font-weight: 700; }
    .activity-user { min-width: 150px; font-weight: 700; color: #0f172a; line-height: 1.25; }
    .activity-title { max-width: 300px; font-weight: 700; color: #0f172a; }
    .activity-type { display: inline-flex; align-items: center; padding: 0.32rem 0.62rem; border-radius: 999px; background: #f1f5f9; color: #475569; font-size: 0.82rem; font-weight: 700; text-transform: capitalize; }
    .activity-amount { color: #0f172a; font-weight: 800; white-space: nowrap; }
    .activity-status { display: inline-flex; align-items: center; gap: 0.42rem; padding: 0.34rem 0.62rem; border-radius: 999px; font-size: 0.82rem; font-weight: 800; white-space: nowrap; }
    .activity-status-dot { width: 7px; height: 7px; border-radius: 999px; }
    .activity-status-success { background: #dcfce7; color: #15803d; }
    .activity-status-warning { background: #fef3c7; color: #b45309; }
    .activity-status-danger { background: #fee2e2; color: #b91c1c; }
    .activity-status-primary { background: #dbeafe; color: #1d4ed8; }
    .activity-status-success .activity-status-dot { background: #16a34a; }
    .activity-status-warning .activity-status-dot { background: #f59e0b; }
    .activity-status-danger .activity-status-dot { background: #ef4444; }
    .activity-status-primary .activity-status-dot { background: #2563eb; }
    .activity-date { min-width: 112px; line-height: 1.25; }
    .activity-date-main { font-weight: 700; color: #0f172a; white-space: nowrap; }
    .activity-date-time { color: #64748b; font-size: 0.82rem; margin-top: 0.18rem; }
</style>
@endsection

@section('content')
@php
    $formatActivityDate = function ($date) {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $date = $date->copy()->timezone(config('app.timezone'));

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    };

    $formatActivityTime = function ($date) {
        return $date->copy()->timezone(config('app.timezone'))->format('H.i');
    };
@endphp

<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reports.transactions') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Filter Period</label>
                <select name="period" class="form-select border-0 bg-light" onchange="toggleReportCustomDate(this); this.form.submit()">
                    <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div class="col-md-6 report-custom-date {{ $period === 'custom' ? '' : 'd-none' }}">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Start Date</label>
                        <input type="date" name="start_date" class="form-control border-0 bg-light" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">End Date</label>
                        <input type="date" name="end_date" class="form-control border-0 bg-light" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md text-end">
                <div class="text-muted small">Showing data for:</div>
                <div class="fw-bold text-primary">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
                <div class="mt-3">
                    <a href="{{ route('admin.reports.transactions.export', request()->except('page')) }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.reports.transactions.export-excel', request()->except('page')) }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-primary-subtle text-primary"><i class="bi bi-receipt"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Total Transactions</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalTransactions) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Paid Transactions</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($paidTransactions) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Pending Transactions</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($pendingTransactions) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card report-card transaction-chart-card h-100">
            <div class="card-header border-0 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="bi bi-bar-chart text-primary me-2"></i>Transactions per Month</h5>
                        <div class="text-muted small">Monthly purchase activity in the selected period</div>
                    </div>
                    <span class="chart-summary-badge">{{ number_format($totalTransactions) }} total transactions</span>
                </div>
            </div>
            <div class="card-body">
                @if($transactionData->sum() > 0)
                    <div class="transaction-chart-shell">
                        <canvas id="transactionChart"></canvas>
                    </div>
                @else
                    <div class="chart-empty-state">
                        <span><i class="bi bi-inbox me-2"></i>No transaction data in this period.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card report-card activity-table-card">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="bi bi-activity text-primary me-2"></i>Transaction Activities</h5>
                        <div class="text-muted small">Activities from {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}</div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">{{ number_format($activities->total()) }} records</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover activity-table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 text-center">View</th>
                                <th>No</th>
                                <th>User</th>
                                <th>Ebook</th>
                                <th>Activity Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="pe-4">Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $activity)
                                @php
                                    $status = $activity->purchase?->payment_status;
                                    $statusLabel = match ($status) {
                                        'approved' => 'Paid',
                                        'pending' => 'Pending',
                                        'rejected' => 'Rejected',
                                        default => 'Completed',
                                    };
                                    $statusClass = match ($status) {
                                        'approved' => 'success',
                                        'pending' => 'warning',
                                        'rejected' => 'danger',
                                        default => 'primary',
                                    };
                                    $amount = $activity->amount ?? $activity->ebook?->price;
                                @endphp
                                <tr>
                                    <td class="ps-4 text-center">
                                        <a href="{{ route('admin.transaction-activities.show', $activity) }}" class="btn btn-sm btn-outline-secondary activity-view-btn" title="View detail" aria-label="View transaction activity detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                    <td class="activity-no">{{ $activities->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="activity-user">{{ $activity->user?->name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="activity-title text-truncate">{{ $activity->ebook?->title ?? '-' }}</div>
                                    </td>
                                    <td><span class="activity-type">{{ $activity->activity_type }}</span></td>
                                    <td class="activity-amount">{{ $amount !== null ? 'Rp ' . number_format($amount, 0, ',', '.') : 'N/A' }}</td>
                                    <td>
                                        <span class="activity-status activity-status-{{ $statusClass }}">
                                            <span class="activity-status-dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="activity-date">
                                            <div class="activity-date-main">{{ $formatActivityDate($activity->created_at) }}</div>
                                            <div class="activity-date-time">{{ $formatActivityTime($activity->created_at) }}</div>
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <div class="activity-date">
                                            <div class="activity-date-main">{{ $formatActivityDate($activity->updated_at) }}</div>
                                            <div class="activity-date-time">{{ $formatActivityTime($activity->updated_at) }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox me-2"></i>No transaction activities in this period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($activities->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleReportCustomDate(select) {
        select.closest('form').querySelector('.report-custom-date').classList.toggle('d-none', select.value !== 'custom');
    }

    const transactionCanvas = document.getElementById('transactionChart');

    if (transactionCanvas) {
        const transactionCtx = transactionCanvas.getContext('2d');
        const transactionGradient = transactionCtx.createLinearGradient(0, 0, 0, 360);
        transactionGradient.addColorStop(0, 'rgba(20, 184, 166, 0.95)');
        transactionGradient.addColorStop(1, 'rgba(59, 130, 246, 0.75)');

        new Chart(transactionCtx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Transactions',
                    data: @json($transactionData),
                    backgroundColor: transactionGradient,
                    borderColor: 'rgba(15, 118, 110, 0.85)',
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                    maxBarThickness: 72,
                    categoryPercentage: 0.48,
                    barPercentage: 0.82
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#dbeafe',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' transactions';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '20%',
                        ticks: {
                            precision: 0,
                            color: '#64748b',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.22)',
                            drawBorder: false
                        },
                        border: { display: false }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11, weight: '600' }
                        },
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    }
</script>
@endsection
