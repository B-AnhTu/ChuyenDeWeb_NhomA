@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center">Cart Management</h2>
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
            <div class="col-md-10 justify-content-center mx-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $cart)
                            @foreach($cart->cartProducts as $cartProduct)
                                <tr>
                                    <td>{{ $cart->cart_id }}</td>
                                    <td>{{ $cart->user->fullname }}</td> <!-- Tên người dùng -->
                                    <td>{{ $cartProduct->product->product_name }}</td> <!-- Tên sản phẩm -->
                                    <td>{{ $cartProduct->quantity }}</td> <!-- Số lượng -->
                                    <td>
                                        <a href="{{ url('/productDetail/' . $cartProduct->product->slug) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('cart.destroy', $cart->cart_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this item?')" type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 d-flex justify-content-center">
                    {{ $carts->links('pagination::bootstrap-4') }} <!-- Hiển thị phân trang -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
