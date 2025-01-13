@extends('layouts.master')

@section('title', 'Create New Product')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-dark">Create New Product</h1>
                <p class="text-muted">Fill out the form to add a new product to your store.</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
@include('dashboard.partials.error')

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Product Info -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title mb-0">Product Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter product name" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter product description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title mb-0">Product Images</h3>
                        </div>
                        <div class="card-body">
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" onchange="previewImages(event)">
                            <div id="imagePreviewContainer" class="d-flex flex-wrap mt-3"></div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title mb-0">Pricing</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" id="price" class="form-control" placeholder="Enter price" value="{{ old('price') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="compare_price">Compare at Price</label>
                                <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Enter compare price" value="{{ old('compare_price') }}">
                                <small class="form-text text-muted">
                                    Use the "Compare at price" field to show a reduced price.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Product Status -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title mb-0">Product Status</h3>
                        </div>
                        <div class="card-body">
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    <!-- Product Category -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white">
                            <h3 class="card-title mb-0">Product Category</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        @if (!$category->parent_id)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sub_category_id">Sub Category</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                    <option value="">Select Sub Category</option>
                                    @foreach ($categories as $category)
                                        @if ($category->parent_id)
                                            <option value="{{ $category->id }}" {{ old('sub_category_id') == $category->id ? 'selected' : '' }}>-- {{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-right pt-3 pb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Product
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark ml-3">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Image Preview Script -->
<script>
    function previewImages(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';

        if (files.length) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                    img.style.width = '100px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>

@endsection
