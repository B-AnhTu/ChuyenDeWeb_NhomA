<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use App\Rules\NoSpace;
use App\Rules\GmailOnly;
use App\Services\User\UserService;



class UpdateUserRequest extends FormRequest
{
    protected $userService; // Khai báo thuộc tính slugService

    // Constructor
    public function __construct(UserService $userService) 
    {
        $this->userService = $userService;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $slug = $this->route('slug');
        $user = $this->userService->getUserBySlug($slug); // Nên lấy đối tượng người dùng thay vì ID

        if (!$user) {
            throw new \Exception('Người dùng không tồn tại'); // Xử lý trường hợp không tìm thấy người dùng
        }

        $userId = $user->user_id; // Lấy ID từ đối tượng

        $rules = [
            'fullname' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'password' => ['required', 'min:8', 'max:20', new NoSpace],
            'phone' => ['required', 'digits:10', 'regex:/^(\+84|0)([3|5|7|8|9])+([0-9]{8})$/', new NoSpecialCharacters, new NoSpace],
            'address' => ['required', 'string', 'max:255', new NoSpecialCharacters],
        ];

        // Kiểm tra xem email có được cập nhật hay không
        if ($this->filled('email')) {
            $rules['email'] = ['required', 'email', 'max:50', 'unique:users,email,' . $userId . ',user_id'];
        } else {
            $rules['email'] = ['nullable', 'email', 'max:50']; // Nếu không cập nhật, chỉ cần kiểm tra định dạng
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'fullname.required' => 'Vui lòng nhập tên người dùng',
            'image.required' => 'Vui lòng nhập ảnh',
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'email.max' => 'Email không được quá 50 ký tự',
            'phone.digits' => 'Số điện thoại phải có 10 chữ số',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ];
    }
}
