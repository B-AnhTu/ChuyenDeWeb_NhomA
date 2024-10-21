@extends('app')
@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Blog</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Blog Section Begin -->
<section class="blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="blog__sidebar">
                    <div class="blog__sidebar__search">
                        <form action="#">
                            <input type="text" placeholder="Search...">
                            <button type="submit"><span class="icon_search"></span></button>
                        </form>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Categories</h4>
                        <ul>
                            @if(isset($data_cate) && $data_cate->count() > 0)
                            @foreach($data_cate as $cate)
                            <li><a href="#">{{ $cate->category_name }}</a></li>
                            @endforeach
                            @else
                            <li>No categories available.</li>
                            @endif
                        </ul>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Recent News</h4>
                        <div class="blog__sidebar__recent">
                            @if(isset($data_blog))
                            @foreach($data_blog as $blog)
                            <a href="#" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="{{ asset('/img/blog/' .$blog->image) }}" alt="" class="small-image">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6>{{$blog->title}}</h6>
                                    <span>{{$blog->created_at}}</span>
                                </div>
                            </a>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="row">
                    @if(isset($data_blog))
                    @foreach($data_blog as $blog)
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="blog__item">
                            <div class="blog__item__pic">
                                <img src="{{ asset('/img/blog/' .$blog->image) }}" alt="" class="big-img">
                            </div>
                            <div class="blog__item__text">
                                <ul>
                                    <li><i class="fa fa-calendar-o"></i> {{ $blog->created_at }}</li>
                                    <li><i class="fa fa-comment-o"></i> 5</li>
                                </ul>
                                <h5><a href="#">{{$blog->title}}</a></h5>
                                <p>{{substr($blog->short_description, 0, 100)}} </p>
                                <a href="{{ route('blog.index', ['id' => $blog->blog_id])}}" class="blog__btn">READ MORE <span class="arrow_right"></span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <div class="col-lg-12">
                        <div class="product__pagination blog__pagination">
                            <a href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <a href="#"><i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->
@endsection