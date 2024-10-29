@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2 class="text-center">Update Blog</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
    <form action="{{ route('blogAdmin.update', $blog->blog_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $blog->title }}">
        </div>
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea rows="3" name="short_description" class="form-control">{{ $blog->short_description }}</textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea rows="5" name="content" class="form-control">{{ $blog->content }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <img src="{{ asset('img/blog/' . $blog->image) }}" alt="Image" class="img-fluid" style="width: 100px; height: 100px;">
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" class="form-control" readonly value="{{ $blog->user->fullname }}">
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
