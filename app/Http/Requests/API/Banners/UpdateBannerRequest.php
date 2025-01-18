<?php

namespace App\Http\Requests\API\Banners;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
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
            'title' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer',
            'content'=>'nullable|string|max:255',
            'status' => 'required',
            'redirect_url' => 'nullable|max:255',
    ];
    }
    public function messages()
    {
        return [
            'title.required' =>'Tiêu đề không được để trống',
            'title.max'=>'Tiêu đề không được quá 255 kí tự',
            'order.interger'=>'Order phải là sô nguyên',
            'content.string'=>'Content phải là chuỗi kí tự',
            'content.max'=>'Content không được quá 255 kí tự',
            'status.required'=>'Trạng thái là bắt buộc',
            'redirect_url.url'=>'Trường này phải là đường dẫn',
            'redirect_url.max'=>'Trường này không được quá 255 kí tự',
        ];
    }
}
