<?php

namespace App\Http\Requests\API\Lessons;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderLessonRequest extends BaseFormRequest
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
            'lessons' => 'nullable|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'lessons.array' => 'Dữ liệu bài học phải là một mảng.',
            'lessons.*.id.required' => 'ID bài học là bắt buộc.',
            'lessons.*.id.exists' => 'Bài học không tồn tại.',
            'lessons.*.order.required' => 'Thứ tự bài học là bắt buộc.',
            'lessons.*.order.integer' => 'Thứ tự bài học phải là một số nguyên.',
        ];
    }
}
