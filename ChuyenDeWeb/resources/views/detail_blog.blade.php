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
                        <b>Last Updated by on {{$blog->created_at}}</b>
                    </p>
                    <p><strong>Share:</strong> <a href="http://www.facebook.com/share.php?u=" target="_blank">Facebook</a> |
                        <a href="https://twitter.com/share?url=" target="_blank">Twitter</a> |
                        <a href="https://web.whatsapp.com/send?text=" target="_blank">Whatsapp</a> |
                        <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=" target="_blank">Linkedin</a> <b>Visits:</b>
                    </p>
                    <hr />
                    <img class="img-fluid rounded" src="{{ asset('/img/blog/' .$blog->image) }}" alt="">
                    <p class="card-text">
                        {{$blog->content}}
                    </p>
                </div>
                <div class="card-footer text-muted">
                </div>
                @endif
            </div>
        </div>
    </div>
    <!---Comment Section --->
    <div class="row" style="margin-top: -8%">
        <div class="col-md-8">
            <div class="card my-4">
                <h5 class="card-header">Leave a Comment:</h5>
                <div class="card-body">
                    <form name="Comment" method="post">
                        <input type="hidden" name="csrftoken" value="" />
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Enter your fullname" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Enter your Valid email" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Comment" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </form>
                </div>
            </div>
            <!---Comment Display Section --->
            <div class="media mb-4">
                <img class="d-flex mr-3 rounded-circle" src="images/usericon.png" alt="">
                <div class="media-body">
                    <h5 class="mt-0"> <br />
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection