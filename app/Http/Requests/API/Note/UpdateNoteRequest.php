<?php

namespace App\Http\Requests\API\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'duration' => 'required|integer',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'user_id là bắt buộc',
            'user_id.integer' => 'user_id phải là số nguyên',
            'lesson_id.required'=>'lesson_id là bắt buộc',
            'lesson_id.integer' => 'lesson_id phải là số nguyên',
            'duration.required' => 'duration là bắt buộc',
            'duration.integer' => 'duration phải là số nguyên',
        ];
    }
}
