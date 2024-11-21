<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\NoSpecialCharacters;
use App\Rules\SingleSpaceOnly;
use App\Rules\NoSpace;
use App\Rules\GmailOnly;


class UserRoleRequest extends FormRequest
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
            'role' => 'required|in:user,editor,admin',
        ];
    }
    public function messages()
    {
        return [
            'role.required' => 'Vui lòng chọn vai trò người dùng',
            'role.in' => 'Vai trò không hợp lệ',
        ];
    }
}
