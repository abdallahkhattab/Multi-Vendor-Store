@extends('layouts.master')

@section('title', 'Edit Category')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-dark">Edit Category</h1>
                <p class="text-muted">Make changes to the selected category</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
@include('dashboard.partials.error')

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title">Edit Category Details</h3>
            </div>

            <!-- Form Start -->
            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name" required value="{{ old('name', $category->name) }}">
                            </div>
                        </div>

                        <!-- Slug Field (Auto-Filled from Name) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Auto-filled from name" readonly value="{{ old('slug', $category->slug) }}">
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" name="image" id="image" class="form-control" onchange="previewImage(event)">
                                <div class="mt-3">
                                    <img id="imagePreview" src="{{ $category->image ? asset('storage/' . $category->image) : '#' }}" alt="Image Preview" class="img-thumbnail" style="{{ $category->image ? '' : 'display: none;' }}" width="150">
                                </div>
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="archived" {{ old('status', $category->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Image Preview Script -->
<script>
    // Auto-fill slug from name
    document.getElementById('name').addEventListener('input', function () {
        const slugInput = document.getElementById('slug');
        slugInput.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    });

    // Image Preview
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
