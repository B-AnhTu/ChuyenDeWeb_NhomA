@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="text-center">Blog Management</h1>
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
            <div class="col-md-12 d-flex justify-content-end my-3">
                <a href="{{ route('blogAdmin.create') }}" class="btn btn-success">Create Blog</a>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
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
                        <td>{{ $blog->blog_id }}</td>
                        <td>{{ $blog->title }}</td>
                        <td>{{ Str::limit($blog->short_description, 15) }}</td>
                        <td>{{ Str::limit($blog->content, 20) }}</td>
                        <td>{{ $blog->user ? $blog->user->fullname : 'Unknown Author' }}</td>
                        <td>
                            <a href="{{ route('blogAdmin.show', $blog->blog_id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('blogAdmin.edit', $blog->blog_id) }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                            <form action="{{ route('blogAdmin.delete', $blog->blog_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
