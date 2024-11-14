@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Sản phẩm đã xóa</h2>
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

            <div class="col-md-12 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedProducts as $product)
                            <tr>
                                <td>{{ $product->product_id }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td><img src="{{ asset('img/products/' . $product->image) }}"></td>
                                <td>
                                    <!-- Tùy chọn khôi phục hoặc xóa vĩnh viễn -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form action="{{ route('product.restore', $product->product_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button onclick="return confirm('Bạn có muốn khôi phục sản phẩm này không?')" type="submit" class="btn btn-success">Khôi phục</button>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('product.forceDelete', $product->product_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Bạn có muốn xóa sản phẩm này vĩnh viễn không?')" type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $trashedProducts->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection