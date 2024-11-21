@extends('app')
@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('img/banners/blackFriday.gif') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Tin tức</h2>
                    <div class="breadcrumb__option">
                        <a href="{{asset('/')}}">Trang chủ</a>
                        <span>Tin tức</span>
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
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="query" placeholder="Search..." value="{{ request()->input('query') }}">
                            <button type="submit"><span class="icon_search"></span></button>
                        </form>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Bài viết mới</h4>
                        <div class="blog__sidebar__recent">
                            @if(isset($recent_posts) && $recent_posts->count() > 0)
                            @foreach($recent_posts as $post)
                            <a href="{{ route('blog.index', ['slug' => $post->slug])}}" class="blog__sidebar__recent__item">
                                <div class="blog__sidebar__recent__item__pic">
                                    <img src="{{ asset('/img/blog/' .$post->image) }}" alt="" class="small-image">
                                </div>
                                <div class="blog__sidebar__recent__item__text">
                                    <h6>{{$post->title}}</h6>
                                    <span>{{$post->created_at->format('d/m/Y')}}</span>
                                </div>
                            </a>
                            @endforeach
                            @else
                            <p>Không có bài viết mới.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="row">
                    <!-- Kiểm tra kết quả tìm kiếm hoặc danh sách bài viết -->
                    @if($data_blog->isEmpty())
                    <div class="col-12">
                        <p>Không có kết quả nào cho từ khóa "{{ $searchTerm }}"</p>
                    </div>
                    @else
                    @foreach($data_blog as $blog)
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic">
                                <img src="{{ asset('/img/blog/' . $blog->image) }}" alt="" class="big-img">
                            </div>
                            <div class="blog__item__text">
                                <ul>
                                    <li><i class="fa fa-calendar-o"></i> {{ $blog->created_at->format('d/m/Y') }}</li>
                                    <li><i class="fa fa-comment-o"></i> {{ $blog->comments->where('status', 1)->count() }}</li>
                                </ul>
                                <h5><a href="{{ route('blog.index', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a></h5>
                                <p>{{ $blog->short_description }}</p>
                                <a href="{{ route('blog.index', ['slug' => $blog->slug]) }}" class="blog__btn">READ MORE <span class="arrow_right"></span></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @if($totalPages > 1)
                    <div class="col-lg-12">
                        <div class="product__pagination blog__pagination">
                            @if($currentPage > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">
                                <i class="fa fa-long-arrow-left"></i>
                            </a>
                            @endif

                            @php
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $start + 4);
                            if($end - $start < 4) { $start=max(1, $end - 4); } @endphp @if($start> 1)
                                <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">1</a>
                                @if($start > 2)
                                <span>...</span>
                                @endif
                                @endif

                                @for($i = $start; $i <= $end; $i++) @if($i==$currentPage) <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="active">{{ $i }}</a>
                                    @else
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                                    @endif
                                    @endfor

                                    @if($end < $totalPages) @if($end < $totalPages - 1) <span>...</span>
                                        @endif
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">{{ $totalPages }}</a>
                                        @endif

                                        @if($currentPage < $totalPages) <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">
                                            <i class="fa fa-long-arrow-right"></i>
                                            </a>
                                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->
@endsection