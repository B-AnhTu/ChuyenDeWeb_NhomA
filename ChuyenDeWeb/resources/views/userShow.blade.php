@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center py-3">
                <h2>Thông tin người dùng</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('img/profile-picture/' . $user->image) }}" alt="{{ $user->fullname }}" width="100%">
            </div>
            <div class="col-md-6">
                <p>Tên người dùng: {{ $user->fullname }}</p>
                <p>Email: {{ $user->email }}</p>
                <p>Số điện thoại: {{ $user->phone }}</p>
                <p>Địa chỉ: {{ $user->address }}</p>
            </div>
        </div>
    </div>
@endsection
