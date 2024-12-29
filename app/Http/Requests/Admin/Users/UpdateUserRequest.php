<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name'       => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email'      => ['required', 'email', Rule::unique('users','email')->ignore($this->route('user')), 'max:255', 'regex:/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'avatar'     => ['nullable', 'image', 'max:2000']
        ];
    }
    public function messages()
    {
        return [
            // Tên
            'name.required' => 'Tên là bắt buộc.',
            'name.string'   => 'Tên phải là một chuỗi',
            'name.regex'    => 'Tên phải là chữ cái.',
            'name.min'      => 'Tên phải có ít nhất 2 ký tự',
            'name.max'      => 'Tên không được vượt quá 255 ký tự.',

            // Email
            'email.required' => 'Email là bắt buộc.',
            'email.email'    => 'Định dạng email không hợp lệ.',
            'email.unique'   => 'Email đã tồn tại.',
            'email.max'      => 'Email không được vượt quá 255 ký tự.',
            'email.regex'    => 'Định dạng email không hợp lệ.',

            // Avatar
            'avatar.image'  => 'Hình ảnh đại diện phải là một tệp hình ảnh.',
            'avatar.max'    => 'Hình ảnh đại diện không được vượt quá 2MB.',
        ];
    }
}
