@extends('layouts.admin')

@section('title', 'Transaction Activity Details')
@section('page-title', 'Transaction Activity Details')

@section('content')
<div class="card shadow-sm rounded-4">
    <div class="card-body">
        <div class="row g-4">
            @php
                $status = $transactionActivity->purchase?->payment_status;
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
            <div class="col-md-6">
                <h5>No</h5>
                <p class="mb-0">{{ $activityNumber }}</p>
            </div>
            <div class="col-md-6">
                <h5>User</h5>
                <p class="mb-0">{{ $transactionActivity->user->name }}</p>
            </div>
            <div class="col-md-6">
                <h5>Ebook</h5>
                <p class="mb-0">{{ $transactionActivity->ebook->title }}</p>
            </div>
            <div class="col-md-6">
                <h5>Activity Type</h5>
                <p class="mb-0">{{ $transactionActivity->activity_type }}</p>
            </div>
            <div class="col-md-6">
                <h5>Description</h5>
                <p class="mb-0">{{ $transactionActivity->description ?: 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Amount</h5>
                @php($amount = $transactionActivity->amount ?? $transactionActivity->ebook?->price)
                <p class="mb-0">{{ $amount !== null ? 'Rp ' . number_format($amount, 0, ',', '.') : 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h5>Status</h5>
                <p class="mb-0">
                    <span class="d-inline-flex align-items-center gap-2 fw-semibold text-{{ $statusClass }}">
                        <span class="rounded-circle bg-{{ $statusClass }}" style="width: 8px; height: 8px;"></span>
                        {{ $statusLabel }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <h5>Created At</h5>
                <p class="mb-0">{{ $transactionActivity->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
            <div class="col-md-6">
                <h5>Updated At</h5>
                <p class="mb-0">{{ $transactionActivity->updated_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.transaction-activities.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
