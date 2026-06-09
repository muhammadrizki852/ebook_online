@extends('layouts.admin')

@section('title', 'User Report')
@section('page-title', 'User Report')

@section('styles')
<style>
    .report-card { border: 0; border-radius: 16px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); }
    .report-icon { width: 52px; height: 52px; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.4rem; }
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body py-3">
        <form action="{{ route('admin.reports.users') }}" method="GET" class="row g-3 align-items-end">
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
                    <a href="{{ route('admin.reports.users.export', request()->except('page')) }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.reports.users.export-excel', request()->except('page')) }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Total Users</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card report-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="report-icon bg-success-subtle text-success"><i class="bi bi-person-plus"></i></div>
                <div>
                    <div class="text-muted small fw-semibold">Logged-in Users in Period</div>
                    <h2 class="mb-0 fw-bold">{{ number_format($newUsers) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card report-card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history text-info me-2"></i>User Login History</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrationHistory as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </td>
                                <td class="text-muted small">{{ ($user->last_login_at ?? $user->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No user logins found in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleReportCustomDate(select) {
        select.closest('form').querySelector('.report-custom-date').classList.toggle('d-none', select.value !== 'custom');
    }
</script>
@endsection
