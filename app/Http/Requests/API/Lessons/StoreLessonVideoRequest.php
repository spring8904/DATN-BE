<?php

namespace App\Http\Requests\API\Lessons;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonVideoRequest extends BaseFormRequest
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
            'video_file' => 'required|file|mimes:mp4,mov,avi|max:102400',
            'is_free_preview' => 'nullable|in:0,1',
            'content' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'video_file.required' => 'File video không được để trống',
            'video_file.file' => 'File video phải là file',
            'video_file.mimes' => 'File video phải có định dạng mp4, mov, avi',
            'video_file.max' => 'File video không được vượt quá 100MB',
        ];
    }
}
