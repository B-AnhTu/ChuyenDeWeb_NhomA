<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SlugService;

class BlogController extends Controller
{
    protected $slugService; // Khai báo thuộc tính

    // Constructor để nhận SlugService thông qua dependency injection
    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Khởi tạo thuộc tính slugService
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $slug = null)
    {
        // Lấy 5 bài viết gần đây
        $recent_posts = Blog::getRecentPosts();

        // Nếu có slug, tìm bài viết theo slug
        if ($slug) {
            $blog = Blog::findBySlug($slug);

            if (!$blog) {
                return view('404');
            }

            return view('detail_blog', [
                'blog' => $blog,
                'recent_posts' => $recent_posts
            ]);
        }

        $searchTerm = $request->input('query');
        $perPage = 6;

        if ($searchTerm) {
            $data_blog = Blog::searchFullText($searchTerm)->paginate($perPage);
        } else {
            $data_blog = Blog::getAllBlogsQuery()->paginate($perPage); 
        }

        // Lấy tất cả danh mục (Có thể dùng trong phần lọc bài viết)
        $data_cate = Category::all();

        return view('blog', [
            'data_blog' => $data_blog,
            'data_cate' => $data_cate,
            'recent_posts' => $recent_posts,
            'currentPage' => $data_blog->currentPage(),
            'totalPages' => $data_blog->lastPage(),
            'searchTerm' => $searchTerm
        ]);
    }

    public function list()
    {
        $data_blog = Blog::with('user')->paginate(5);

        return view('blogAdmin', compact('data_blog'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'max:100', new NoSpecialCharacters, new SingleSpaceOnly],
            'short_description' => ['required', 'max:255', new NoSpecialCharacters, new SingleSpaceOnly],
            'content' => ['required', new NoSpecialCharacters],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ], [
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn',
            'short_description.max' => 'Mô tả ngắn không được quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $data = $request->all();

        // Tạo slug từ title
        $data['slug'] = $this->slugService->slugify($data['title']); // Sử dụng hàm slugify để tạo slug

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);

            // Cập nhật ảnh mới trong database
            $data['image'] = $filename;
        }

        $blog = Blog::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'content' => $data['content'],
            'user_id' => Auth::user()->user_id,
            'image' => $data['image'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $blog->save();

        return redirect()->route('blogAdmin.index')->with('success', 'Blog created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog = Blog::with('user')->where('slug', $slug)->first();
        if (!$blog) {
            return redirect()->route('blogAdmin.index')->with('error', 'Blog không tồn tại');
        }
        return view('blogShow', ['blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            return redirect()->route('blogAdmin.index')->with('error', 'Blog không tồn tại');
        }
        return view('blogUpdate', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5096',
            'title' => ['required', 'max:100', new NoSpecialCharacters, new SingleSpaceOnly],
            'short_description' => ['required', 'max:255', new NoSpecialCharacters, new SingleSpaceOnly],
            'content' => ['required', new NoSpecialCharacters],
        ], [
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn',
            'short_description.max' => 'Mô tả ngắn không được quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $blog = Blog::where('slug', $slug)->first();

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img/blog'), $filename);

            // Delete old image if exists
            if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
                unlink(public_path('img/blog/' . $blog->image));
            }

            // Update with new image
            $blog->image = $filename;
        }

        $blog->title = $request->input('title');
        $blog->short_description = $request->input('short_description');
        $blog->content = $request->input('content');
        // Tạo slug từ title mới
        $blog->slug = $this->slugService->slugify($request->input('title')); // Sử dụng hàm slugify để tạo slug
        $blog->user_id = Auth::user()->user_id; // Lưu user_id của người đăng nhập đang đăng nhập hiện tại
        $blog->updated_at = now();
        $blog->save();

        return redirect()->route('blogAdmin.index')->with('success', 'Blog updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        // Kiểm tra xem blog có tồn tại không
        $blog = Blog::where('slug', $slug)->first();
        if (!$blog) {
            return redirect()->route('blogAdmin.index')->with('error', 'Blog không tồn tại.');
        }
        // Delete image if exists
        if ($blog->image && file_exists(public_path('img/blog/' . $blog->image))) {
            unlink(public_path('img/blog/' . $blog->image));
        }
        // Thực hiện xóa blog
        try {
            $blog->delete();
            return redirect()->route('blogAdmin.index')->with('success', 'Blog đã được xóa thành công.');
        } catch (\Exception $e) {
            // Xử lý lỗi khi xóa không thành công
            return redirect()->route('blogAdmin.index')->with('error', 'Xóa blog không thành công.');
        }
    }
    // Sắp xếp theo tên, ngày cập nhật (quan ly user)
    public function sortBlogs(Request $request)
    {
        $query = Blog::query();

        // Sắp xếp theo yêu cầu
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'description_asc':
                    $query->orderBy('short_description', 'asc');
                    break;
                case 'description_desc':
                    $query->orderBy('short_description', 'desc');
                    break;
                case 'updated_at_asc':
                    $query->orderBy('updated_at', 'asc');
                    break;
                case 'updated_at_desc':
                    $query->orderBy('updated_at', 'desc');
                    break;
                default:
                    // Mặc định không sắp xếp
                    break;
            }
        }

        $data_blog = $query->paginate(5); // Phân trang

        return view('blogAdmin', compact('data_blog'));
    }
    // Tìm kiếm blog
    public function searchBlogs(Request $request)
    {
        $query = Blog::query();
        $searchTerm = $request->input('query');

        // Tìm kiếm full-text ưu tiên theo title trước, sau đó là content
        if ($searchTerm) {
            $searchWords = explode(' ', $searchTerm);
            $searchWords = array_filter($searchWords, function ($word) {
                return strlen($word) >= 2; // Chỉ lấy những từ có độ dài từ 2 ký tự trở lên
            });

            if (!empty($searchWords)) {
                // Tạo truy vấn fulltext
                $searchQuery = '+' . implode('* +', $searchWords) . '*';
                $query->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$searchQuery]);
            }
        }

        // Sắp xếp theo thứ tự ưu tiên và phân trang
        $data_blog = $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 ELSE 2 END", ["%$searchTerm%"])
            ->paginate(5);

        // Truyền dữ liệu tìm kiếm vào view
        return view('blogAdmin', [
            'data_blog' => $data_blog,
            'searchTerm' => $searchTerm,
        ]);
    }
}
