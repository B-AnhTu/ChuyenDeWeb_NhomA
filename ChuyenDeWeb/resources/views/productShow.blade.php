@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-3 mb-3">
                <h2>Thông tin sản phẩm</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/products/' . $product->image) }}" alt="{{ $product->product_name }}" width="70%">
            </div>
            <div class="col-md-6">
                <div class="info me-1">
                    <p>Tên sản phẩm: {{ $product->product_name }}</p>
                    <p>Price: {{ $product->price }}</p>
                    <p>Số lượng hàng còn: {{ $product->stock_quantity }}</p>
                    <p>Mô tả: {{ $product->description }}</p>
                    <p>Số lượng hàng đã bán: {{ $product->sold_quantity }}</p>
                    <p>Lượt xem: {{ $product->product_view }}</p>
                </div>
                
            </div>            
        </div>
    </div>
@endsection
