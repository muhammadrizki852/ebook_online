@extends('layouts.admin')

@section('title', 'Edit Ebook')
@section('page-title', 'Edit Ebook')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.ebooks.update', $ebook) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmUpdateEbook(this)">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $ebook->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" value="{{ old('author', $ebook->author) }}" class="form-control @error('author') is-invalid @enderror" required>
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price (Rp)</label>
                    <input type="number" name="price" step="1" value="{{ old('price', $ebook->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $ebook->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $ebook->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $ebook->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $ebook->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="form-control @error('cover_image') is-invalid @enderror">
                    @if($ebook->cover_image)
                        <small class="text-muted">Current file: {{ basename($ebook->cover_image) }}</small>
                    @endif
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">PDF File</label>
                    <input type="file" name="file_path" accept="application/pdf" class="form-control @error('file_path') is-invalid @enderror">
                    @if($ebook->file_path)
                        <small class="text-muted">Current file: {{ basename($ebook->file_path) }}</small>
                    @endif
                    @error('file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Ebook</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmUpdateEbook(form) {
        const status = form.querySelector('[name="status"]').value;
        const currentStatus = @json($ebook->status);

        if (status === 'published' && currentStatus !== 'published') {
            return confirm('Yakin ingin publish ebook ini? Setelah update, buku akan langsung tampil di aplikasi.');
        }

        if (status === 'published') {
            return confirm('Yakin ingin update ebook yang sudah published? Perubahan akan langsung tampil di aplikasi.');
        }

        return confirm('Yakin ingin menyimpan perubahan ebook ini?');
    }
</script>
@endsection
