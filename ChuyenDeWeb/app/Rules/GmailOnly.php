<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GmailOnly implements Rule
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
        // Kiểm tra xem email có kết thúc bằng @gmail.com không
        return preg_match('/^[^@]+@gmail\.com$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Trường :attribute phải là một địa chỉ email hợp lệ và chỉ được phép sử dụng @gmail.com một lần.';
    }
}