<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $slug = null)
    {
        // Lấy bài viết mới nhất cho sidebar
        $recent_posts = Blog::orderBy('created_at', 'desc')->take(5)->get();

        // Trường hợp có slug (chi tiết blog)
        if ($slug) {
            try {
                // Tìm bài viết theo slug
                $blog = Blog::where('slug', $slug)->first();

                if (!$blog) {
                    return view('404');
                }

                return view('detail_blog', [
                    'blog' => $blog,
                    'recent_posts' => $recent_posts
                ]);
            } catch (\Exception $e) {
                // Nếu có lỗi, chuyển đến trang 404
                return view('404');
            }
        }

        // Số bài viết trên mỗi trang
        $perPage = 6;
        // Lấy số trang hiện tại từ query parameter, mặc định là 1
        $currentPage = $request->query('page', 1);

        // Lấy tất cả bài viết và phân trang
        $data_blog = Blog::orderBy('created_at', 'desc')
            ->skip(($currentPage - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Lấy tổng số bài viết để tính số trang
        $totalPosts = Blog::count();
        $totalPages = ceil($totalPosts / $perPage);

        // Kiểm tra nếu trang hiện tại vượt quá tổng số trang
        if ($currentPage > $totalPages && $totalPages > 0) {
            return redirect()->route('blog.index', ['page' => 1]);
        }

        $data_cate = Category::getAllCate();

        return view('blog', [
            'data_blog' => $data_blog,
            'data_cate' => $data_cate,
            'recent_posts' => $recent_posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts
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
            'short_description' => ['required','max:255', new NoSpecialCharacters, new SingleSpaceOnly],
            'content' => ['required', new NoSpecialCharacters, new SingleSpaceOnly],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ],[
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
        $data['slug'] = $this->slugify($data['title']); // Sử dụng hàm slugify để tạo slug

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
            return redirect()->route('blogAdmin.index')->with('error', 'Blog not found');
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
            return redirect()->route('blogAdmin.index')->with('error', 'Blog not found');
        }
        return view('blogUpdate', ['blog' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => ['required', 'max:100', new NoSpecialCharacters, new SingleSpaceOnly],
            'short_description' => ['required','max:255', new NoSpecialCharacters, new SingleSpaceOnly],
            'content' => ['required', new NoSpecialCharacters, new SingleSpaceOnly],
        ],[
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
        $blog->slug = $this->slugify($request->input('title')); // Sử dụng hàm slugify để tạo slug
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
        $searchQuery = $request->input('query');

        // Tìm kiếm theo thứ tự ưu tiên: title trước, sau đó là short_description
        $data_blog = Blog::where('title', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('short_description', 'LIKE', '%' . $searchQuery . '%')
            ->orderByRaw("CASE WHEN title LIKE '%$searchQuery%' THEN 1 ELSE 2 END") // Ưu tiên title
            ->paginate(5); // Phân trang

        return view('blogAdmin', compact('data_blog'));
    }
    // Hàm để tạo slug từ title
    private function slugify($text)
    {
        // Chuyển đổi ký tự có dấu thành không dấu
        $text = $this->removeVietnameseAccent($text);
        
        // Thay thế nhiều khoảng trắng thành một khoảng trắng
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text); // Xóa khoảng trắng ở đầu và cuối
        $text = strtolower($text); // Chuyển thành chữ thường
        $text = str_replace(' ', '-', $text); // Thay dấu khoảng trắng bằng dấu gạch nối

        return $text;
    }

    // Hàm để loại bỏ dấu tiếng Việt
    private function removeVietnameseAccent($string)
    {
        $unicode = [
            'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'đ' => 'd',
            'À' => 'A', 'Á' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
            'Ă' => 'A', 'Ằ' => 'A', 'Ắ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
            'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
            'È' => 'E', 'É' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
            'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ò' => 'O', 'Ó' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
            'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
            'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
            'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ỳ' => 'Y', 'Ý' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y',
            'Đ' => 'D',
        ];
        return strtr($string, $unicode);
    }
    
}
