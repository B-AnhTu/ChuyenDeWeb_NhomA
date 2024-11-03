@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Quản lý bình luận đã duyệt</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="col-md-10 justify-content-center mx-auto">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Người dùng</th>
                                    <th>Nội dung</th>
                                    <th>Ngày duyệt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $review)
                                <tr>
                                    <td>{{ $review->review_id }}</td>
                                    <td>{{ $review->product->product_name }}</td>
                                    <td>
                                        {{ $review->user->fullname }}<br>
                                        <small>{{ $review->user->email }}</small>
                                    </td>
                                    <td>{{ $review->comment }}</td>
                                    <td>{{ $review->updated_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('reviews.reject', $review->review_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Hủy duyệt</button>
                                        </form>
                                        <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $reviews->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection