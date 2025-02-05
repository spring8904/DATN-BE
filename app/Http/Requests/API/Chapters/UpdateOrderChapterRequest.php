<?php

namespace App\Http\Requests\API\Chapters;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderChapterRequest extends BaseFormRequest
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
            'chapters' => 'nullable|array',
            'chapters.*.id' => 'required|exists:chapters,id',
            'chapters.*.order' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'chapters.array' => 'Dữ liệu chương học phải là một mảng.',
            'chapters.*.id.required' => 'ID chương học là bắt buộc.',
            'chapters.*.id.exists' => 'Chương học không tồn tại.',
            'chapters.*.order.required' => 'Thứ tự chương học là bắt buộc.',
            'chapters.*.order.integer' => 'Thứ tự chương học phải là một số nguyên.',
        ];
    }
}
