<?php

namespace App\Http\Requests\API\Chapters;

use App\Http\Requests\API\Bases\BaseFormRequest;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChapterRequest extends BaseFormRequest
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
            'title' => 'required|string|max:255',
            'status' => 'nullable|in:0,1'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'status.in' => 'Trạng thái không hợp lệ'
        ];
    }

}
