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
        // Kiểm tra xem chuỗi có chỉ chứa một khoảng trắng giữa các từ không
        return preg_match('/^\S+(?: \S+)*$/', trim($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute không được phép có khoảng trắng ở đầu hoặc cuối, và chỉ được phép có một khoảng trắng giữa các từ.';
    }
}