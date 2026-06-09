@extends('layouts.admin')

@section('title', 'Manual Book')
@section('page-title', 'Manual Book')

@section('styles')
<style>
    .manual-hero {
        background: linear-gradient(135deg, #111827 0%, #2563eb 55%, #14b8a6 100%);
        border-radius: 18px;
        color: #fff;
        overflow: hidden;
        position: relative;
    }
    .manual-hero::after {
        content: "";
        position: absolute;
        right: -80px;
        top: -120px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,.14);
    }
    .manual-panel {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 42px rgba(15, 23, 42, .08);
    }
    .manual-dropzone {
        border: 2px dashed #c7d2fe;
        border-radius: 16px;
        background: #f8fafc;
        padding: 28px;
        transition: border-color .2s, background .2s;
    }
    .manual-dropzone:hover {
        border-color: #4f46e5;
        background: #eef2ff;
    }
    .manual-preview {
        min-height: 520px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: #0f172a;
    }
    .manual-empty {
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #64748b;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
    }
</style>
@endsection

@section('content')
<div class="manual-hero p-4 p-lg-5 mb-4">
    <div class="position-relative" style="z-index:1;">
        <span class="badge bg-white text-primary mb-3">Help Center</span>
        <h2 class="fw-bold mb-2">Kelola Manual Book</h2>
        <p class="mb-0 text-white-50">
            Upload PDF panduan pengguna. File terbaru akan otomatis tampil di menu Help user.
        </p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="manual-panel p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $manualBook ? 'Ganti PDF Manual' : 'Upload PDF Manual' }}</h5>
                    <p class="text-muted small mb-0">Format PDF, maksimal 10 MB.</p>
                </div>
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:46px;height:46px;">
                    <i class="bi bi-cloud-arrow-up fs-4"></i>
                </div>
            </div>

            <form action="{{ route('admin.manual-books.store') }}" method="POST" enctype="multipart/form-data" data-confirm="{{ $manualBook ? 'Upload PDF baru dan ganti Manual Book saat ini?' : 'Upload Manual Book ini?' }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul</label>
                    <input type="text" name="title" value="{{ old('title', $manualBook?->title ?? 'Manual Book') }}" class="form-control @error('title') is-invalid @enderror" maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="manual-dropzone mb-3">
                    <label class="form-label fw-semibold d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                        File PDF
                    </label>
                    <input type="file" name="manual_pdf" accept="application/pdf" class="form-control @error('manual_pdf') is-invalid @enderror" required>
                    <div class="form-text">File baru akan menggantikan file lama untuk semua user.</div>
                    @error('manual_pdf')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-upload me-1"></i>{{ $manualBook ? 'Ganti Manual Book' : 'Upload Manual Book' }}
                </button>
            </form>
        </div>

        @if($manualBook)
            <div class="manual-panel p-4 mt-4">
                <h5 class="fw-bold mb-3">File Aktif</h5>
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-filetype-pdf fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $manualBook->title }}</div>
                        <div class="text-muted small">{{ $manualBook->original_filename }}</div>
                        <div class="text-muted small">{{ $manualBook->readable_file_size }} - updated {{ $manualBook->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('manual-book.show') }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-1"></i> Preview
                    </a>
                    <a href="{{ route('manual-book.download') }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    <form action="{{ route('admin.manual-books.destroy', $manualBook) }}" method="POST" class="d-inline" data-confirm="Hapus Manual Book ini? User tidak bisa membuka PDF sampai admin upload file baru.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-7">
        @if($manualBook && $manualBook->file_url)
            <div class="manual-preview">
                <iframe src="{{ $manualBook->file_url }}" title="Preview Manual Book" width="100%" height="620" style="border:0;"></iframe>
            </div>
        @else
            <div class="manual-empty">
                <div>
                    <i class="bi bi-file-earmark-pdf display-4 d-block mb-3 text-danger"></i>
                    <h5 class="fw-bold text-dark">Belum ada Manual Book</h5>
                    <p class="mb-0">Upload PDF pertama agar user dapat membukanya dari menu Help.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
