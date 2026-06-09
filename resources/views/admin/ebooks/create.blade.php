@extends('layouts.admin')

@section('title', 'Add Ebook')
@section('page-title', 'Add Ebook')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCreateEbook(this)">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" value="{{ old('author') }}" class="form-control @error('author') is-invalid @enderror" required>
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price (Rp)</label>
                    <input type="number" name="price" step="1" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-control @error('cover_image') is-invalid @enderror">
                    <div class="form-text">JPG, PNG, atau WebP. Maksimal 5MB.</div>
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">PDF File</label>
                    <input type="file" name="file_path" accept="application/pdf" class="form-control @error('file_path') is-invalid @enderror">
                    <div class="form-text">Wajib kalau status Published. Maksimal 50MB.</div>
                    @error('file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Ebook</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmCreateEbook(form) {
        const status = form.querySelector('[name="status"]').value;
        if (status === 'published') {
            return confirm('Yakin ingin publish ebook ini? Setelah disimpan, buku akan langsung tampil di aplikasi.');
        }

        return confirm('Yakin ingin menyimpan ebook ini sebagai draft?');
    }
</script>
@endsection
