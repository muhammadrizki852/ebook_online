@extends('layouts.admin')

@section('title', 'Ebook Report')
@section('page-title', 'Ebook Report')

@section('styles')
<style>
    .report-card { border: 0; border-radius: 16px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); }
    .report-icon { width: 52px; height: 52px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; }
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reports.ebooks') }}" method="GET" class="row g-3 align-items-end">
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
                    <a href="{{ route('admin.reports.ebooks.export', request()->except('page')) }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.reports.ebooks.export-excel', request()->except('page')) }}" class="btn btn-outline-success btn-sm">
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
                <div class="report-icon bg-primary-subtle text-primary"><i class="bi bi-journals"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Total Ebooks</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalEbooks) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-success-subtle text-success"><i class="bi bi-gift"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Total Free Ebooks</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalFreeEbooks) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-warning-subtle text-warning"><i class="bi bi-tag"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Total Paid Ebooks</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalPaidEbooks) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-1 fw-bold"><i class="bi bi-trophy text-warning me-2"></i>Top Selling Ebooks</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ebook</th>
                            <th>Category</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSellingEbooks as $ebook)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $ebook->title }}</div>
                                    <div class="text-muted small">{{ $ebook->author }}</div>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $ebook->category }}</span></td>
                                <td class="text-end fw-semibold">{{ number_format($ebook->sales_count) }}</td>
                                <td class="text-end">Rp {{ number_format((int) $ebook->revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No ebook sales found in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-1 fw-bold"><i class="bi bi-pie-chart text-info me-2"></i>Ebooks by Category</h5>
                <div class="text-muted small">Paid purchases and free ebooks by category in the selected period</div>
            </div>
            <div class="card-body">
                <canvas id="ebookCategoryChart" style="min-height: 320px;"></canvas>
            </div>
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

    new Chart(document.getElementById('ebookCategoryChart'), {
        type: 'doughnut',
        data: {
            labels: @json($categoryLabels),
            datasets: [{
                data: @json($categoryData),
                backgroundColor: ['#4f46e5', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9', '#22c55e']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endsection
