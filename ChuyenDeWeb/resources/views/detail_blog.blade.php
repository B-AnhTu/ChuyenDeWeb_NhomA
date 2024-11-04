@extends('app')
@section('content')

<!-- Page Content -->
<div class="container">
    <div class="row" style="margin-top: 4%">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <!-- Blog Post -->
            <div class="card mb-4">
                @if(isset($blog))
                <div class="card-body">
                    <h2 class="card-title">{{$blog->short_description}}</h2>
                    <p>
                        <b>Cập nhật lần cuối vào lúc: {{$blog->updated_at->format('d/m/Y')}}</b>
                    </p>
                    <p><strong>Chia sẻ:</strong> <a href="http://www.facebook.com/share.php?u=" target="_blank">Facebook</a> |
                        <a href="https://twitter.com/share?url=" target="_blank">Twitter</a> |
                        <a href="https://web.whatsapp.com/send?text=" target="_blank">Whatsapp</a> |
                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=" target="_blank">Linkedin</a>
                    </p>
                    <hr />
                    <img class="img-fluid rounded" src="{{ asset('/img/blog/' .$blog->image) }}" alt="">
                    <p class="card-text">
                        {!! nl2br(e($blog->content)) !!}
                    </p>
                </div>
                <div class="card-footer text-muted">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!---Comment Section --->
    <div class="row">
        <div class="col-md-8">
            <div class="card my-4">
                <h5 class="card-header">Hãy để lại bình luận:</h5>
                <div class="card-body">
                    <form id="commentForm">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->blog_id }}">
                        @if (Auth::check())
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->fullname }}" readonly>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        @else
                        <div class="alert alert-info">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá
                        </div>
                        @endif
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Bình luận...." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Comment Display Section -->
            <div class="card my-4">
                <h5 class="card-header">Bình luận:</h5>
                <div class="card-body">
                    @if(isset($comments))
                    @foreach($comments as $comment)
                    <div class="comment">
                        <h6>{{ $comment->user ? $comment->user->fullname : 'Người dùng ẩn danh' }}
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </h6>
                        <p>{{ $comment->content }}</p>
                    </div>
                    <hr class="my-3">
                    @endforeach
                    @else
                    <p>Chưa có bình luận nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: "{{ route('comments.store') }}",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Bình luận của bạn đã được gửi thành công và đang chờ phê duyệt.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6f42c1'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra, vui lòng thử lại.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
</script>

@endsection