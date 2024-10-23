<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpecialCharacters implements Rule
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
        // Kiểm tra xem chuỗi có chứa ký tự đặc biệt không
        return !preg_match('/[^a-zA-Z0-9\s]/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute không được chứa ký tự đặc biệt.';
    }
}