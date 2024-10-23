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
                <form action="{{ route('product.update', $product->product_id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $product->product_name }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea rows="5" name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" name="price" id="price" class="form-control" value="{{ $product->price }}">
                    </div>
                    <div class="form-group">
                        <label for="sold_quantity">Sold Quantity</label>
                        <input type="text" name="sold_quantity" id="sold_quantity" class="form-control" value="{{ $product->sold_quantity }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity</label>
                        <input type="text" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}">
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="manufacturer_id">Manufacturer</label>
                        <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                            @foreach ($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->manufacturer_id }}" {{ $product->manufacturer_id == $manufacturer->manufacturer_id ? 'selected' : '' }}>{{ $manufacturer->manufacturer_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <img src="{{ asset('img/products/' . $product->image) }}" alt="Product Image" class="img-fluid" style="width: 100px; height: 100px;">
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary my-3 mx-auto">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
