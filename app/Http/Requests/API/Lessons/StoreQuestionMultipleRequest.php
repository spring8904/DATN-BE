<?php

namespace App\Http\Requests\API\Lessons;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionMultipleRequest extends BaseFormRequest
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
        $rules = [
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'questions.*.answer_type' => 'required|string|in:multiple_choice,single_choice',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.answer' => 'required|string|max:255',
            'questions.*.options.*.is_correct' => 'required|boolean',
            'questions.*.description' => 'nullable|string',
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $questions = $this->input('questions', []);

            foreach ($questions as $index => $question) {
                if ($question['answer_type'] === 'single_choice') {
                    $correctAnswers = collect($question['options'])
                        ->where('is_correct', true)
                        ->count();

                    if ($correctAnswers !== 1) {
                        $validator->errors()->add("questions.$index.options", 'Câu hỏi dạng single_choice chỉ được có đúng một đáp án đúng.');
                    }
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'questions' => collect($this->questions)->map(function ($question) {
                $question['options'] = collect($question['options'])->map(function ($option) {
                    $option['is_correct'] = filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
                    return $option;
                })->toArray();
                return $question;
            })->toArray(),
        ]);
    }

    public function message()
    {
        return [
            'question.required' => 'Câu hỏi không được để trống',
            'question.string' => 'Câu hỏi phải là chuỗi',
            'question.max' => 'Câu hỏi không được quá 255 ký tự',
            'image.image' => 'Ảnh không đúng định dạng',
            'image.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif, svg',
            'image.max' => 'Ảnh không được quá 2048 ký tự',
            'answer_type.required' => 'Loại câu trả lời là bắt buộc.',
            'answer_type.in' => 'Loại câu trả lời không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi',
            'options.required' => 'Câu trả lời không được để trống',
            'options.array' => 'Câu trả lời phải là mảng',
            'options.*.answers.required' => 'Câu trả lời không được để trống',
            'options.*.answers.array' => 'Câu trả lời phải là mảng',
            'options.*.answers.min' => 'Câu trả lời phải có ít nhất 2 đáp án',
            'options.*.answers.*.answer.required' => 'Đáp án không được để trống',
            'options.*.answers.*.answer.string' => 'Đáp án phải là chuỗi',
            'options.*.answers.*.answer.max' => 'Đáp án không được quá 255 ký tự',
            'options.*.answers.*.is_correct.boolean' => 'Đáp án đúng phải là boolean',
            'options.*.answers.*.is_correct.nullable' => 'Đáp án đúng không được để trống',
        ];
    }

}
