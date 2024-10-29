@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-3">
                <h2>Category Details</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/category/' . $category->image) }}" alt="{{ $category->category_name }}" width="80%">                
            </div>
            <div class="col-md-6">
                <h5>Tên danh mục: {{ $category->category_name }}</h5>
            </div>
        </div>
    </div>
@endsection
