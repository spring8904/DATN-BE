<?php

namespace App\Http\Requests\API\WishList;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishListRequest extends FormRequest
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
            //
            'courses_id' => 'required|integer|exists:courses,id'
        ];
    }


    public function messages()
    {
        return [
            //
            'course_id.required' => 'Khóa học là bắt buộc',
            'course_id.exists' => 'Khóa học khôn tồn tại',
        ];
    }
}
