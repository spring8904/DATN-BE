<?php

namespace App\Http\Requests\API\Search;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends BaseFormRequest
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
            'q' => 'required|string|min:2|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => 'Vui lòng nhập từ khóa tìm kiếm.',
            'q.string' => 'Từ khóa tìm kiếm không hợp lệ',
            'q.min' => 'Từ khóa tìm kiếm phải có ít nhất 2 ký tự.',
            'q.max' => 'Từ khóa tìm kiếm không được vượt quá 255 ký tự.',
        ];
    }
}
