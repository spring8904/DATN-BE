<?php

namespace App\Http\Requests\API\Chapters;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends BaseFormRequest
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
            'slug' => 'required|exists:courses,slug',
            'title' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'slug.required' => 'Vui lòng nhập slug khoá học',
            'slug.exists' => 'Khoá học không tồn tại',
            'title.required' => 'Vui lòng nhập tiêu đề chương học',
            'title.string' => 'Tiêu đề chương học phải là chuỗi',
            'title.max' => 'Tiêu đề chương học không được vượt quá 255 ký tự',
        ];
    }
}
