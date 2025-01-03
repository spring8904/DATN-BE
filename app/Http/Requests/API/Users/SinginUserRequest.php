<?php

namespace App\Http\Requests\API\Users;

use Illuminate\Foundation\Http\FormRequest;

class SinginUserRequest extends FormRequest
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
            'email'      => ['required', 'email', 'max:255', 'regex:/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password'   => ['required', 'string', 'min:8', 'max:255', 'regex:/^(?=.*[A-Z])/'],
        ];
    }
    public function messages()
    {
        return [
            // Email
            'email.required' => 'Email là bắt buộc.',
            'email.email'    => 'Định dạng email không hợp lệ.',
            'email.unique'   => 'Email đã tồn tại.',
            'email.max'      => 'Email không được vượt quá 255 ký tự.',
            'email.regex'    => 'Định dạng email không hợp lệ.',

            // Mật khẩu
            'password.required'  => 'Mật khẩu là bắt buộc.',
            'password.string'    => 'Định dạng mật khẩu không hợp lệ.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.max'       => 'Mật khẩu không được vượt quá 255 ký tự.',
            'password.regex'     => 'Mật khẩu phải chứa ít nhất một chữ cái viết hoa.',
        ];
    }
}
