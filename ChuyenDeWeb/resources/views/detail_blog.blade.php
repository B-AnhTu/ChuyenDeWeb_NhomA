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
                <h5 class="card-header">Leave a Comment:</h5>
                <div class="card-body">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->blog_id }}">

                        <div class="form-group">
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->fullname }}" readonly>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Comment" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>

            <!-- Comment Display Section -->
            <h5>Comments:</h5>
            @if(isset($comments) && $comments->count() > 0)
            @foreach ($comments as $comment)
            <div class="media mb-4">
                <img class="d-flex mr-3 rounded-circle" src="images/usericon.png" alt="">
                <div class="media-body">
                    <h5 class="mt-0">{{ $comment->user->fullname }} <br />
                        <span style="font-size:11px;"><b>at</b> {{ $comment->created_at }}</span>
                    </h5>
                    {{ $comment->content }}
                </div>
            </div>
            @endforeach
            @else
            <p>No comments available.</p>
            @endif
        </div>
    </div>
</div>
</div>

@endsection