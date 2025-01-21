<?php

namespace App\Http\Requests\API\Documents;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'document_url' => 'nullable|url',
        ];
    }
    public function messages()
    {
        return [
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'content.string' => 'Nội dung phải là chuỗi ký tự.',

            'document_file.file' => 'Tệp tài liệu phải là một tệp hợp lệ.',
            'document_file.mimes' => 'Tệp tài liệu phải có định dạng pdf, doc hoặc docx.',
            'document_file.max' => 'Dung lượng tệp không được vượt quá 2MB.',
            
            'document_url.url' => 'URL tài liệu không hợp lệ.',
        ];
    }
}
