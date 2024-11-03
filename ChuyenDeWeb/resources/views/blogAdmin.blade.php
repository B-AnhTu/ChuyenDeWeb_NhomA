@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Blog Management</h2>
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
                        <form action="{{ route('sortBlogs') }}" method="get">
                            <select class="form-control me-2" name="sort_by" onchange="this.form.submit()">
                                <option value="" disabled selected>Sắp xếp theo</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (Từ A - Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên (Từ Z - A)</option>
                                <option value="description_asc" {{ request('sort_by') == 'description_asc' ? 'selected' : '' }}>Mô tả (Từ A - Z)</option>
                                <option value="description_desc" {{ request('sort_by') == 'description_desc' ? 'selected' : '' }}>Mô tả (Từ Z - A)</option>
                                <option value="created_at_asc" {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>Ngày tạo (Tăng dần)</option>
                                <option value="created_at_desc" {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Ngày tạo (Giảm dần)</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex" action="{{ route('searchBlogs') }}" method="GET">
                            @csrf
                            <input name="query" class="form-control me-2" type="text" placeholder="Search Blog" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end my-3">
                <a href="{{ route('blogAdmin.create') }}" class="btn btn-success">Create Blog</a>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Short Description</th>
                        <th>Content</th>
                        <th>Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_blog as $blog)
                    <tr>
                        <td>{{ $loop->iteration + ($data_blog->currentPage() - 1) * $data_blog->perPage() }}</td> <!-- Sequential number -->
                        <td>{{ $blog->title }}</td>
                        <td>{{ Str::limit($blog->short_description, 15) }}</td>
                        <td>{{ Str::limit($blog->content, 20) }}</td>
                        <td>{{ $blog->user ? $blog->user->fullname : 'Unknown Author' }}</td>
                        <td>
                            <a href="{{ route('blogAdmin.show', $blog->slug) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('blogAdmin.edit', $blog->slug) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                            <form action="{{ route('blogAdmin.delete', $blog->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $data_blog->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
