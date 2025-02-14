<?php

namespace App\Http\Requests\API\Comments;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'user_id' => 'required',
            'content' => 'required|max:2000',
            'commentable_id' => 'required',
            'commentable_type' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'Người dùng là bắt buộc',
            'content.required' => 'Nội dung là bắt buộc',
            'content.max' => 'Nội dung không được quá 2000 kí tự',
            'commentable_id.required' => 'commentable_id là bắt buộc',
            'commentable_type.required' => 'commentable_type là bắt buộc'
        ];
    }
}
