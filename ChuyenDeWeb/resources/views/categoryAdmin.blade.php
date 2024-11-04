@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Category Management</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="col-md-12 mt-3">
                <div class="row mb-3 d-flex align-items-center">
                    <div class="col-md-3">
                        <form action="{{ route('sortCategories') }}" method="get">
                            <select class="form-control me-2" name="sort_by" onchange="this.form.submit()">
                                <option value="" disabled selected>Sắp xếp theo</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (Từ A - Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên (Từ Z - A)</option>
                                <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Ngày tạo (Tăng dần)</option>
                                <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Ngày tạo (Giảm dần)</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex" action="{{ route('searchCategories') }}" method="GET">
                            @csrf
                            <input name="query" class="form-control me-2" type="text" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('category.create') }}" class="btn btn-primary my-3">Add Category</a>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td> <!-- Sequential number -->
                                <td>{{ $category->category_name }}</td>
                                <td><img src="{{ asset('img/category/'. $category->image) }}"
                                        alt="{{ $category->category_name }}" width="50"></td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('category.show', $category->slug) }}"
                                        class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('category.edit', $category->slug) }}"
                                        class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('category.delete', $category->slug) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this category?')" type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $categories->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection