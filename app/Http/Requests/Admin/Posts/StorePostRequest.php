<?php

namespace App\Http\Requests\Admin\Posts;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'categories'    => ['required', 'exists:categories,id'],
            'title'         => ['required','string','max:255'],
            'description'   => ['nullable','string','max:255'],
            'content'       => ['nullable','string'],
            'thumbnail'     => ['nullable','image'],
            'status'        => ['in:draft,pending,published,private'],
            'view'          => ['nullable','integer','min:0'],
            'is_hot'        => ['nullable', 'boolean'],
            'published_at'  => ['nullable', 'date'],
        ];
    }

    public function messages()
    {
        return [

            'categories.required'     => 'Danh mục không được để trống',
            'categories.exists'       => 'Danh mục không tồn tại',

            'title.required'          => 'Tên tiêu đề không được trống',
            'title.string'            => 'Tên tiêu đề phải nhập là một chuỗi',
            'title.max'               => 'Tên tiêu đề không được quá 255 kí tự',

            'description.required'    => 'Mô tả không được để trống',
            'description.max'         => 'Tên không được quá 255 kí tự',

            'content.string'          => 'Tên phải nhập là một chuỗi',

        ];
    }
}
