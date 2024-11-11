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
        // Kiểm tra xem chuỗi chỉ chứa một khoảng trắng giữa các từ
        return preg_match('/^(?:\S+ ?)+\S+$/u', trim($value));   
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute chỉ được phép có một khoảng trắng giữa các từ.';
    }
}