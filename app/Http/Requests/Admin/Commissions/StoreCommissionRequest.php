<?php

namespace App\Http\Requests\Admin\Commissions;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommissionRequest extends FormRequest
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
            'difficulty_level' => 'required|in:easy,medium,difficult,very_difficult',
            'system_percentage' => 'required|numeric',
            'instructor_percentage' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'difficulty_level.required' => 'Vui lòng chọn cấp độ',
            'difficulty_level.in' => 'Cấp độ không hợp lệ',
            'system_percentage.required' => 'Vui lòng chọn phần trăm của hệ thống',
            'instructor_percentage.required' => 'Vui lòng chọn phần trăm của giáo viên',
        ];
    }
}
