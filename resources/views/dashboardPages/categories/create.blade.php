@extends('layouts.master')

@section('content')

<!-- Main content -->
@include('dashboard.partials.error')
<section class="content-header">					
    <div class="container-fluid my-2">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="{{ url()->previous() }}" class="btn btn-primary">back</a>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Create New Category</h5>
            </div>

            <!-- Form Start -->
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                <div class="card-body">
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter category name" required value="{{ old('name') }}">
                            </div>
                        </div>
            
                        <!-- Slug Field (Auto-Filled from Name) -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" placeholder="Enter slug" readonly>
                            </div>
                        </div>
            
                        <!-- Description Field -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter description">{{ old('description') }}</textarea>
                            </div>
                        </div>
            
                        <!-- Image Upload -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image" class="form-control" onchange="previewImage(event)">
            
                                <!-- Image Preview -->
                                <div class="mt-2">
                                    <img id="imagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="display: none;" width="150">
                                </div>
                            </div>
                        </div>
            
                        <!-- Status Field -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
            
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Buttons -->
                <div class="card-footer d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary ms-3">Cancel</a>
                </div>
            </form>
            
            <script>
                function previewImage(event) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const preview = document.getElementById('imagePreview');
                        preview.src = reader.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            </script>
            
        </div>
    </div>
</section>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
