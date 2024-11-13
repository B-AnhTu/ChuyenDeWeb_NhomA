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
                        <form action="{{route('sortProducts')}}" method="get">
                            <select class="form-control me-2" name="sort_by" onchange="this.form.submit()">
                                <option value="" disabled selected>Sắp xếp theo</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (Từ
                                    A - Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên
                                    (Từ Z - A)</option>
                                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Giá
                                    (Tăng dần)</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Giá
                                    (Giảm dần)</option>
                                <option value="views_asc" {{ request('sort_by') == 'views_asc' ? 'selected' : '' }}>Lượt
                                    xem (Tăng dần)</option>
                                <option value="views_desc" {{ request('sort_by') == 'views_desc' ? 'selected' : '' }}>Lượt
                                    xem (Giảm dần)</option>
                                <option value="purchases_asc" {{ request('sort_by') == 'purchases_asc' ? 'selected' : '' }}>Số lượng mua (Tăng dần)</option>
                                <option value="purchases_desc" {{ request('sort_by') == 'purchases_desc' ? 'selected' : '' }}>Số lượng mua (Giảm dần)</option>
                                <option value="stock_asc" {{ request('sort_by') == 'stock_asc' ? 'selected' : '' }}>Số
                                    lượng hàng tồn (Tăng dần)</option>
                                <option value="stock_desc" {{ request('sort_by') == 'stock_desc' ? 'selected' : '' }}>Số
                                    lượng hàng tồn (Giảm dần)</option>
                                <option value="updated_at_asc" {{ request('sort_by') == 'updated_at_asc' ? 'selected' : '' }}>Ngày cập nhật (cũ nhất)</option>
                                <option value="updated_at_desc" {{ request('sort_by') == 'updated_at_desc' ? 'selected' : '' }}>Ngày cập nhật (mới nhất)</option>
                            </select>
                            <!-- <button class="btn btn-outline-success" type="submit">Lọc</button> -->
                        </form>
                    </div>
                    <div class="col-md-6 mx-auto">
                        <form class="d-flex" action="{{ route('searchProducts') }}" method="GET">
                            @csrf
                            <input name="query" class="form-control me-2" type="text" placeholder="Search"
                                aria-label="Search" value="{{ request('query') }}">
                            <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-md-3 d-flex justify-content-end">
                        <a href="{{ route('product.trashed')}}" class="btn btn-info my-3 me-2">History</a>
                        <a href="{{ route('product.create') }}" class="btn btn-primary my-3">Add Product</a>
                    </div>
                </div>
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
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <!-- Sequential number -->
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>{{ $product->product_view }}</td>
                                <td>{{ Str::limit($product->description, 10) }}</td>
                                <td>{{ $product->sold_quantity }}</td>
                                <td><img src="{{ asset('img/products/' . $product->image) }}"
                                        alt="{{ $product->product_name }}" width="100"></td>
                                <td>
                                    <!-- Add action buttons here -->
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('product.edit', $product->slug) }}" class="btn btn-primary"><i
                                            class="fas fa-pencil-alt"></i></a>
                                    <form action="{{ route('product.delete', $product->slug) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure you want to delete this product?')"
                                            type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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