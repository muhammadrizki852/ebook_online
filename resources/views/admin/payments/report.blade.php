@extends('layouts.admin')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('styles')
<style>
    .chart-card { transition: transform 0.2s; }
    .chart-card:hover { transform: translateY(-3px); }
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body py-3">
        <form id="filterForm" action="{{ route('admin.payments.report') }}" method="GET" class="row g-3 align-items-end">
            {{-- Keep hidden fields to preserve search and per_page when changing period --}}
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="per_page" value="{{ request('per_page', 20) }}">
            
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Filter Period</label>
                <select name="period" class="form-select border-0 bg-light" onchange="toggleCustomDate(this.value); this.form.submit()">
                    <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div id="custom-date-fields" class="col-md-6 {{ $period == 'custom' ? '' : 'd-none' }}">
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
                <div class="fw-bold text-primary mb-2">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
                <a href="{{ route('admin.payments.export', request()->all()) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('admin.payments.export-excel', request()->all()) }}" class="btn btn-outline-success btn-sm ms-1">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card chart-card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-graph-up text-primary me-2"></i>Daily Revenue 
                    <span class="text-muted fw-normal small ms-1">
                        - {{ match($period) {
                            '7days' => 'Last 7 Days',
                            '30days' => 'Last 30 Days',
                            'this_month' => 'This Month',
                            'last_month' => 'Last Month',
                            'custom' => 'Custom Range',
                            default => 'Last 30 Days'
                        } }}
                    </span>
                </h5>
                <span class="badge bg-primary-subtle text-primary">IDR</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="min-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart text-info me-2"></i>Sales by Category</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="categoryChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-primary-subtle text-primary me-3">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Revenue</div>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-success-subtle text-success me-3">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Successful Sales</div>
                        <h3 class="mb-0 fw-bold">{{ $totalSales }}</h3>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-warning-subtle text-warning me-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Pending Payments</div>
                        <h3 class="mb-0 fw-bold">{{ $pendingSales }}</h3>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">{{ $salesSummaryTitle }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">{{ $salesSummaryPeriodLabel }}</th>
                                <th>Sales Count</th>
                                <th class="text-end pe-4">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesSummary as $summaryData)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $summaryData->label }}</td>
                                    <td>{{ $summaryData->count }}</td>
                                    <td class="text-end pe-4 fw-bold text-success">Rp {{ number_format($summaryData->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No sales data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">Top Selling Ebooks</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($topEbooks as $item)
                        <li class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ $item->ebook->cover_url }}" alt="" class="rounded" style="width: 40px; height: 60px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $item->ebook->title }}</div>
                                    <div class="small text-muted">{{ $item->count }} sold</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">Rp {{ number_format($item->revenue, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item px-0 py-3 text-center text-muted border-0">
                            No ebooks sold yet.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0 fw-bold">Transaction List for this Period</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary me-2">{{ $filteredTransactions->total() }} Records</span>
                        
                        <form action="{{ route('admin.payments.report') }}" method="GET" class="d-flex align-items-center gap-2">
                            {{-- Preserve existing filters --}}
                            <input type="hidden" name="period" value="{{ request('period', '30days') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text border-0 bg-light text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Search transactions..." value="{{ request('search') }}">
                            </div>

                            <select name="per_page" class="form-select form-select-sm border-0 bg-light" style="width: 100px;" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / page</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / page</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                            </select>
                            
                            <button type="submit" class="btn btn-sm btn-primary">Go</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 text-start" width="120">Action</th>
                                <th width="140">Date</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Ebook</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($filteredTransactions as $sale)
                                <tr>
                                    <td class="ps-4 text-start">
                                        <button class="btn btn-sm btn-outline-info" style="min-width: 85px;" onclick="showInvoice('{{ route('admin.payments.invoice', $sale) }}')">
                                            <i class="bi bi-receipt me-1"></i>Invoice
                                        </button>
                                    </td>
                                    <td class="small text-muted">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="small fw-bold text-primary">{{ $sale->midtrans_order_id ?? 'TRX-'.$sale->id }}</td>
                                    <td>
                                        <div class="small fw-semibold">{{ $sale->user->name ?? 'Guest' }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $sale->user->email ?? '-' }}</div>
                                    </td>
                                    <td><div class="small text-truncate" style="max-width: 220px;">{{ $sale->ebook->title }}</div></td>
                                    <td class="fw-bold text-success small">Rp {{ number_format($sale->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-4 text-muted">No successful transactions in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Showing {{ $filteredTransactions->firstItem() }} to {{ $filteredTransactions->lastItem() }} of {{ $filteredTransactions->total() }} results
                    </div>
                    <div>
                        {{ $filteredTransactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-warning">Pending Transactions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 text-start">Action</th>
                                <th class="ps-4">Order ID</th>
                                <th>User</th>
                                <th>Ebook</th>
                                <th>Method</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingTransactions as $pending)
                                <tr>
                                    <td class="ps-4 text-start">
                                        <button class="btn btn-sm btn-outline-info" onclick="showInvoice('{{ route('purchase.invoice', $pending) }}')">
                                            <i class="bi bi-receipt me-1"></i>Invoice
                                        </button>
                                    </td>
                                    <td class="ps-4 small fw-bold text-warning">{{ $pending->midtrans_order_id ?? 'PEND-'.$pending->id }}</td>
                                    <td>
                                        <div class="small fw-semibold">{{ $pending->user->name }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $pending->user->email }}</div>
                                    </td>
                                    <td><div class="small text-truncate" style="max-width: 200px;">{{ $pending->ebook->title }}</div></td>
                                    <td><span class="badge bg-light text-dark border small">{{ $pending->midtrans_payment_type ? strtoupper($pending->midtrans_payment_type) : 'Waiting...' }}</span></td>
                                    <td class="small text-muted">{{ $pending->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">No pending transactions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Showing {{ $pendingTransactions->firstItem() }} to {{ $pendingTransactions->lastItem() }} of {{ $pendingTransactions->total() }} results
                    </div>
                    <div>
                        {{ $pendingTransactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart (Line Chart)
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const gradient = ctxRevenue.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Revenue (IDR)',
                data: @json($chartData),
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                fill: true,
                borderWidth: 3,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // Category Chart (Doughnut Chart)
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: @json($categoryData->pluck('category')),
            datasets: [{
                data: @json($categoryData->pluck('count')),
                backgroundColor: [
                    '#4f46e5', '#10b981', '#f59e0b', '#3b82f6', '#ef4444', '#8b5cf6', '#ec4899'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 }
                    }
                }
            },
            cutout: '70%'
        }
    });

    function toggleCustomDate(value) {
        const fields = document.getElementById('custom-date-fields');
        if (value === 'custom') {
            fields.classList.remove('d-none');
        } else {
            fields.classList.add('d-none');
        }
    }

    // Invoice Modal handling (existing script)
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
