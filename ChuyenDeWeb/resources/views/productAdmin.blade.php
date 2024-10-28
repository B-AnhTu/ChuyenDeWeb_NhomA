@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Product Management</h2>
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
                        <select class="form-control" name="filter" id="filter">
                            <option value="" disabled selected>Sắp xếp</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <a href="{{ route('product.create') }}" class="btn btn-primary my-3">Add Product</a>
            </div>
            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
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
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td> <!-- Sequential number -->
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
                                    <a href="{{ route('product.show', $product->product_id) }}"
                                        class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('product.edit', $product->product_id) }}"
                                        class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('product.delete', $product->product_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this product?')" type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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