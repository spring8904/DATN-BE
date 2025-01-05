<?php

namespace App\Http\Requests\API\Courses;

use App\Http\Requests\API\Bases\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentCourse extends BaseFormRequest
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
            'category_id' => 'nullable|integer|exists:categories,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'intro' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,3gp|max:204800',
            'price' => 'nullable|numeric|min:0',
            'price_sale' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $price = $this->input('price');
                    $priceSale = $value;

                    if ($price > 0 && $priceSale < $price * 0.3) {
                        $fail('Số tiền giảm không được nhỏ hơn 30% so với giá gốc.');
                    }

                    if ($price > 0 && $priceSale > $price) {
                        $fail('Số tiền giảm không được lớn hơn giá gốc.');
                    }
                }
            ],
            'level' => [
                'nullable',
                'string',
                Rule::in([
                    'beginner',
                    'intermediate',
                    'advanced',
                ])
            ],
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'qa' => 'nullable|array',
            'qa.*.question' => 'required|string|max:255',
            'qa.*.answer' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'category_id.integer' => 'ID danh mục phải là số nguyên.',
            'category_id.exists' => 'Không tìm thấy danh mục.',
            'name.string' => 'Tên khoá học phải là chuỗi ký tự.',
            'name.max' => 'Tên khoá học không được vượt quá 500 ký tự.',
            'description.string' => 'Mô tả khoá học phải là chuỗi ký tự.',
            'description.max' => 'Mô tả khoá học không được vượt quá 255 ký tự.',
            'thumbnail.image' => 'Thumbnail phải là ảnh.',
            'thumbnail.mimes' => 'Thumbnail phải có định dạng jpeg, png, jpg, gif, webp.',
            'thumbnail.max' => 'Thumbnail không được vượt quá 2MB.',
            'price.numeric' => 'Giá khoá học phải là số.',
            'price.min' => 'Giá khoá học phải lớn hơn 0.',
            'price_sale.numeric' => 'Giá giảm phải là số.',
            'price_sale.min' => 'Giá giảm phải lớn hơn 0.',
            'price_sale.function' => 'Số tiền giảm không được nhỏ hơn 30% so với giá gốc.',
            'level.string' => 'Level phải là chuỗi ký tự.',
            'level.in' => 'Level không hợp lệ.',
            'requirements.array' => 'Yêu cầu phải là mảng.',
            'benefits.array' => 'Lợi ích phải là mảng.',
            'qa.array' => 'Câu hỏi và trả lời phải là mảng.',
        ];
    }
}
