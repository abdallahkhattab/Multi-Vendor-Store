@extends('layouts.master')

@section('title', $category->name)

@section('content')
<!-- Main Content -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $category->name }} Details</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>
</section>

@include('dashboard.partials.error')

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Category: {{ $category->name }}</h5>
            </div>

            <div class="card-body">
                <h6>Category Details</h6>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Name:</strong> {{ $category->name }}</li>
                    <li class="list-group-item"><strong>Slug:</strong> {{ $category->slug }}</li>
                    <li class="list-group-item"><strong>Status:</strong> 
                        @if ($category->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="card-footer">
                <h6>Products in this Category</h6>
                <table class="table table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}" width="60" alt="{{ $product->name }}">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->slug }}</td>
                                <td>
                                    @if ($product->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
