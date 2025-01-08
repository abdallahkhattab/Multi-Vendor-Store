@extends('layouts.master')

@section('content')

@include('dashboard.partials.success')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6 text-right"> 
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> New Category
                </a>
                <a href="{{ route('deletedCategories') }}" class="btn btn-info">
                    <i class="fas fa-trash-alt"></i> Deleted Category
                </a>
            </div>
            

           
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <form action="{{ route('categories.index') }}" method="GET">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ request()->search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="120">Image</th>
                            <th width="120">Name</th>
                            <th width="120">Slug</th>
                            <th width="120">Parent</th>
                            <th width="100">Status</th>
                            <th width="125">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $category->image) }}" width="100" alt="{{ $category->name }}">
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->parent_id ?? 'N/A' }}</td>
                            <td>
                                @if ($category->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination m-0 float-right">
                    {{ $categories->links() }}
                </ul>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>



@endsection
