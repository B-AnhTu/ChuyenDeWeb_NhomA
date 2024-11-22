<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;


class UpdateProductRequest extends FormRequest
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
            'product_name' => ['required', 'string', 'max:50', new NoSpecialCharacters, new SingleSpaceOnly],
            'price' => 'required|numeric|min:0|regex:/^(0|[1-9][0-9]*)(000)*$/', // Chia hết cho 1000            
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
            'description' => ['required', new NoSpecialCharacters, new SingleSpaceOnly],
            'stock_quantity' => 'required|integer|min:0|max:1000', 
            'manufacturer_id' => 'required',
            'category_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm',
            'product_name.max' => 'Tên sản phẩm không được quá 50 ký tự',
            'price.required' => 'Vui lòng nhập giá sản phẩm',
            'price.numeric' => 'Giá sản phẩm bắt buộc phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0',
            'price.regex' => 'Giá sản phẩm phải chia hết cho 1000',
            'image.mimes' => 'Hình ảnh phải có đuôi .jpeg, .jpg, .png, .gif',
            'image.max' => 'Kích thước tối đa của hình là 5MB',
            'description.required' => 'Vui lòng nhập chi tiết sản phẩm',
            'stock_quantity.required' => 'Vui lòng nhập số lượng hàng tồn kho',
            'stock_quantity.integer' => 'Số lượng hàng tồn kho phải là số nguyên',
            'stock_quantity.min' => 'Số lượng hàng tồn kho phải lớn hơn 0',
            'stock_quantity.max' => 'Số lượng hàng tồn không vượt quá 1000',
            'manufacturer_id.required' => 'Nhà sản xuất không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
        ];
    }
}
