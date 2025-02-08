<?php

namespace App\Http\Requests\Admin\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
            'name'        => ['required','string','max:255'],
            'parent_id'   => ['nullable','integer','exists:categories,id'],
            'status'      => [Rule::in(0,1)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
            'name.string'   => 'Tên phải là một chuỗi',
            'name.max'      => 'Tên không được quá 255 kí tự',
            'parent_id.integer' => 'Danh mục cha không hợp lệ',
            'status.in'     => 'Trạng thái không hợp lệ',
        ];
    }
}
