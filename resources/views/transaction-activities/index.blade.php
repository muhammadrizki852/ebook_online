@extends('layouts.admin')

@section('title', 'Transaction Activities')
@section('page-title', 'Transaction Activities')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h1 class="h3 mb-0">Transaction Activities</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

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

        return $date->format('d') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y, H.i');
    };
@endphp

<div class="card shadow-sm rounded-4">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Actions</th>
                        <th>No</th>
                        <th>User</th>
                        <th>Ebook</th>
                        <th>Activity Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
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
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.transaction-activities.show', $activity) }}" class="btn btn-sm btn-outline-secondary me-1">View</a>
                            </td>
                            <td>{{ $activities->firstItem() + $loop->index }}</td>
                            <td>{{ $activity->user?->name ?? '-' }}</td>
                            <td>{{ $activity->ebook?->title ?? '-' }}</td>
                            <td>{{ $activity->activity_type }}</td>
                            <td>
                                @php($amount = $activity->amount ?? $activity->ebook?->price)
                                {{ $amount !== null ? 'Rp ' . number_format($amount, 0, ',', '.') : 'N/A' }}
                            </td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-2 fw-semibold text-{{ $statusClass }}">
                                    <span class="rounded-circle bg-{{ $statusClass }}" style="width: 8px; height: 8px;"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $formatActivityDate($activity->created_at) }}</div>
                                <div class="small text-muted">{{ $activity->user?->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $formatActivityDate($activity->updated_at) }}</div>
                                <div class="small text-muted">{{ $activity->user?->name ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                Belum ada data transaction activities.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $activities->links() }}
</div>
@endsection
