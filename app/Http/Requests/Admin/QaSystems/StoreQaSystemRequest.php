<?php

namespace App\Http\Requests\Admin\QaSystems;

use Illuminate\Foundation\Http\FormRequest;

class StoreQaSystemRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'answer_type' => 'required|string|in:single,multiple',
            'status' => 'required|string|in:1,0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'description.required' => 'Vui lòng nhập mô tả',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự',
            'question.required' => 'Vui lòng nhập câu hỏi',
            'question.max' => 'Câu hỏi không được vượt quá 255 ký tự',
            'options.required' => 'Vui lòng nhập đáp án',
            'options.min' => 'Đáp án phải có ít nhất 2 lựa chọn',
            'options.*.required' => 'Vui lòng nhập đáp án',
            'answer_type.required' => 'Vui lòng chọn loại câu hỏi',
            'answer_type.in' => 'Loại câu hỏi không hợp lệ',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}
