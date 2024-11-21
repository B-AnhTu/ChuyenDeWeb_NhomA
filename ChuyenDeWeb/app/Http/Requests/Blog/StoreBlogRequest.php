<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;


class StoreBlogRequest extends FormRequest
{
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
        return [
            'title' => ['required', 'max:100', new NoSpecialCharacters, new SingleSpaceOnly],
            'short_description' => ['required', 'max:255', new NoSpecialCharacters, new SingleSpaceOnly],
            'content' => ['required', new NoSpecialCharacters],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ];
    }
    public function messages()
    {
        return [
            'image.required' => 'Vui lòng chọn hình ảnh để tải lên',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg, .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn',
            'short_description.max' => 'Mô tả ngắn không được quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
        ];
    }
}
