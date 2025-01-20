<?php

namespace App\Http\Requests\API\Lessons;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends BaseFormRequest
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
            'chapter_id' => 'required|integer|exists:chapters,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:video,document,quiz,coding',
        ];
    }

    public function messages()
    {
        return [
            'chapter_id.required' => 'Vui lòng chọn chương học',
            'chapter_id.exists' => 'Chương học không tồn tại',
            'title.required' => 'Vui lòng nhập tên bài học',
            'title.max' => 'Tên bài học không được vượt quá 255 ký tự',
            'type.required' => 'Vui lòng chọn loại bài học',
            'type.in' => 'Loại bài học không hợp lệ',
        ];
    }
}
