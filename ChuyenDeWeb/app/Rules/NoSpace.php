<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpace implements Rule
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
        // Kiểm tra xem chuỗi có chứa khoảng trắng không
        return !preg_match('/\s/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute không được chứa khoảng trắng.';
    }
}