<?php

namespace App\Http\Requests\Manufacturer;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;


class UpdateManufacturerRequest extends FormRequest
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
            'manufacturer_name' => ['required', 'string', 'max:50', new SingleSpaceOnly, new NoSpecialCharacters],
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120', 
        ];
    }
    public function messages()
    {
        return [
            'manufacturer_name.required' => 'Vui lòng nhập tên nhà sản xuất',
            'manufacturer_name.max' => 'Tên nhà sản xuất không được quá 50 ký tự',
            'image.mimes' => 'Vui lòng chọn hình ảnh có đuôi hợp lệ như .png, .jpeg. .jpg',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
        ];
    }
}
