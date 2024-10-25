@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2 class="text-center">Create Blog</h2>
    <form action="{{ route('blogAdmin.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea name="short_description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" class="form-control" readonly>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
@endsection
