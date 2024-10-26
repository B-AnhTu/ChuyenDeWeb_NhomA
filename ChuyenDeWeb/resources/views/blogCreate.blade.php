@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h2 class="text-center">Create Blog</h2>
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
    <form action="{{ route('blogAdmin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter title">
        </div>
        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea rows="3" name="short_description" class="form-control" placeholder="Enter short description"></textarea>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea rows="5" name="content" class="form-control" placeholder="Enter content"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" name="author" class="form-control" readonly value="{{ Auth::check() ? Auth::user()->fullname : 'Guest' }}">
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>
@endsection
