@extends('app')
@section('content')
<div class="container mt-5 mb-5">
    <h2 class="text-center">Đổi Mật Khẩu</h2>

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Form đổi mật khẩu -->
    <form action="{{ route('change-password') }}" method="POST" class="mt-4">
        @csrf

        <!-- Trường nhập mật khẩu hiện tại -->
        <div class="form-group">
            <label for="current_password">Mật khẩu hiện tại</label>
            <div class="input-group">
                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
            </div>
            @error('current_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Trường nhập mật khẩu mới -->
        <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <div class="input-group">
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" required>
            </div>
            @error('new_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Trường nhập lại mật khẩu mới -->
        <div class="form-group">
            <label for="new_password_confirmation">Nhập lại mật khẩu mới</label>
            <div class="input-group">
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            @error('new_password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nút Lưu -->
        <button type="submit" class="btn btn-primary btn-block">Lưu</button>
    </form>
</div>

@endsection
