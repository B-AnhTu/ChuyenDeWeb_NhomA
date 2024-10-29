@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-3">
                <h2>Blog Details</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/blog/' . $blog->image) }}" alt="{{ $blog->title }}" width="80%">                
            </div>
            <div class="col-md-6">
                <h3>Tiêu đề: {{ $blog->title }}</h3><br>
                <h5>Mô tả ngắn: {{ $blog->short_description }}</h5>
                <p>Nội dung: {{ $blog->content }}</p>
                <p>Ngày tạo: {{ $blog->created_at }}</p>
                <p>Sửa lần cuối: {{ $blog->updated_at }}</p>
                <p>Tác giả: {{ $blog->user->fullname }}</p>
                <div class="col-md-12 d-flex justify-content-center">
                    <a href="{{ route('blogAdmin.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
