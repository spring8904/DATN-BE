<?php

namespace App\Http\Requests\API\Lessons;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends BaseFormRequest
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
            'question' => 'required|string|max:255',
            'description' => 'nullable|string',
            'answer_type' => 'required|in:single_choice,multiple_choice',
            'options' => 'required|array|min:2|max:5',
            'options.*.answer' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'options' => collect($this->input('options', []))->map(function ($option) {
                return [
                    'answer' => $option['answer'] ?? '',
                    'is_correct' => filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                ];
            })->toArray(),
        ]);
    }

    public function message()
    {
        return [
            'question.required' => 'Câu hỏi là bắt buộc.',
            'question.string' => 'Câu hỏi phải là chuỗi',
            'question.max' => 'Câu hỏi không được quá 255 ký tự',
            'description.string' => 'Mô tả phải là chuỗi',
            'answer_type.required' => 'Loại câu trả lời là bắt buộc.',
            'answer_type.in' => 'Loại câu trả lời không hợp lệ.',
            'options.required' => 'Danh sách câu trả lời là bắt buộc.',
            'options.array' => 'Danh sách câu trả lời phải là mảng.',
            'options.min' => 'Danh sách câu trả lời phải có ít nhất 2 phần tử.',
            'options.max' => 'Danh sách câu trả lời không được quá 5 phần tử.',
            'options.*.answer.required' => 'Câu trả lời là bắt buộc.',
            'options.*.answer.string' => 'Câu trả lời phải là chuỗi.',
            'options.*.is_correct.required' => 'Trạng thái đúng/sai là bắt buộc.',
            'options.*.is_correct.boolean' => 'Trạng thái đúng/sai phải là boolean.',
        ];
    }
}
