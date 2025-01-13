@extends('layouts.master')

@section('title','Deleted Categories')
@section('content')

@include('dashboard.partials.success')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard/Deleted Categories</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ url()->previous()}}" class="btn btn-primary">back</a>

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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trashedCategories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                <!-- Restore Button -->
                                <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                
                                <!-- Force Delete Button -->
                                <form action="{{ route('categories.forceDelete', $category->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to permanently delete this category?')">
                                        <i class="fas fa-trash-alt"></i> Delete Permanently
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No trashed categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                
            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination m-0 float-right">
                    {{ $trashedCategories->links() }}
                </ul>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>



@endsection
