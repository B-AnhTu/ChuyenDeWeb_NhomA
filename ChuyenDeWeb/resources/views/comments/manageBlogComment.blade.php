@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="mb-0">Quản lý bình luận đã duyệt</h2>
                </div>
                @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif
                <div class="col-md-10 justify-content-center mx-auto">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($comments) && $comments->count() > 0)
                            @foreach ($comments as $comment)
                            <tr>
                                <td>{{ $comment->comment_id }}</td>
                                <td>{{ $comment->user->fullname }}</td> <!-- Hiển thị fullname của user -->
                                <td>{{ $comment->user->email }}</td> <!-- Hiển thị email của user -->
                                <td>{{ $comment->content }}</td>
                                <td>Approved</td>
                                <td>
                                    <form action="{{ route('comments.disapprove', $comment->comment_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Disapprove</button>
                                    </form>
                                    <form action="{{ route('comments.destroy', $comment->comment_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Bạn có muốn xóa bình luận này?')" type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="col-md-12 d-flex justify-content-center">
                        {{ $comments->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection