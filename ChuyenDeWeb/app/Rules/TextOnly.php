<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TextOnly implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Kiểm tra xem chuỗi có chỉ chứa chữ cái viết hoa, thường
        return preg_match('/^[A-Za-z]+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute chỉ được chứa chữ cái viết hoa, thường và không có số hoặc ký tự đặc biệt.';
    }
}