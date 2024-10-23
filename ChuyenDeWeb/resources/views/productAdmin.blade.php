@extends('layouts.dashboard')
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
        /* Adjust the gap between buttons as needed */
    }

    .action-buttons form {
        display: inline;
    }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Product</h2>
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
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('product.create') }}" class="btn btn-primary my-3">Add Product</a>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product name</th>
                            <th>Price</th>
                            <th>Stock quantity</th>
                            <th>Product view</th>
                            <th>Description</th>
                            <th>Sold quantity</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->product_id }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $product->product_view }}</td>
                                <td>{{ Str::limit($product->description, 10) }}</td>
                                <td>{{ $product->sold_quantity }}</td>
                                <td><img src="{{ asset('img/products/' . $product->image) }}"
                                        alt="{{ $product->product_name }}" width="50"></td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('product.edit', $product->product_id) }}"
                                        class="btn btn-primary">Edit</a>
                                    <form action="{{ route('product.delete', $product->product_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this product?')" type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection