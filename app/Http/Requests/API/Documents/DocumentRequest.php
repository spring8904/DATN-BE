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
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,ppt,png,jpg,jpeg',
            'document_url' => 'nullable|url|required_without:document_file',
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',
            'document_file.required' => 'Vui lòng tải lên tài liệu.',
            'document_file.mimes' => 'Tài liệu phải có định dạng pdf, doc, docx, xls, ppt, png, jpg, jpeg.',
            'document_url.required_without' => 'URL tài liệu là bắt buộc khi không tải lên tệp tài liệu.',
            'document_url.url' => 'URL tài liệu không hợp lệ.',
        ];
    }
}
