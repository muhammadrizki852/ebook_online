@extends('layouts.admin')

@section('title', 'Edit Transaction Activity')
@section('page-title', 'Edit Transaction Activity')

@section('content')
<div class="card shadow-sm rounded-4">
    <div class="card-body">
        <form action="{{ route('admin.transaction-activities.update', $transactionActivity) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $transactionActivity->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="ebook_id" class="form-label">Ebook</label>
                    <select name="ebook_id" id="ebook_id" class="form-select @error('ebook_id') is-invalid @enderror" required>
                        <option value="">Select Ebook</option>
                        @foreach($ebooks as $ebook)
                            <option value="{{ $ebook->id }}" {{ old('ebook_id', $transactionActivity->ebook_id) == $ebook->id ? 'selected' : '' }}>{{ $ebook->title }}</option>
                        @endforeach
                    </select>
                    @error('ebook_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="activity_type" class="form-label">Activity Type</label>
                <input type="text" name="activity_type" id="activity_type" value="{{ old('activity_type', $transactionActivity->activity_type) }}" class="form-control @error('activity_type') is-invalid @enderror" required>
                @error('activity_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $transactionActivity->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $transactionActivity->amount) }}" class="form-control @error('amount') is-invalid @enderror">
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.transaction-activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Activity</button>
            </div>
        </form>
    </div>
</div>
@endsection