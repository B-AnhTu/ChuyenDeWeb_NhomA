<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SingleSpaceOnly implements Rule
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
        // Kiểm tra xem chuỗi có chỉ chứa chữ cái và một khoảng trắng giữa các từ không
        return preg_match('/^[A-Za-z]+(?: [A-Za-z]+)*$/', trim($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute chỉ được phép chứa chữ cái và một khoảng trắng giữa các từ, không có khoảng trắng ở đầu hoặc cuối.';
    }
}