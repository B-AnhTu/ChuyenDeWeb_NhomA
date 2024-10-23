@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center">Create Product</h2>
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
                <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea rows="4" name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" name="price" id="price" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity</label>
                        <input type="text" name="stock_quantity" id="stock_quantity" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="manufacturer_id">Manufacturer</label>
                        <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                            @foreach ($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->manufacturer_id }}">{{ $manufacturer->manufacturer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary my-3 mx-auto">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
