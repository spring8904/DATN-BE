<?php

namespace App\Http\Requests\Admin\Commissions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommissionRequest extends FormRequest
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
            'difficulty_level' => 'required',
            'system_percentage' => 'required',
            'instructor_percentage' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'difficulty_level.required' => 'Vui lòng chọn độ khó',
            'system_percentage.required' => 'Vui lòng chọn phần trăm của hệ thống',
            'instructor_percentage.required' => 'Vui lòng chọn phần trăm của giáo viên',
        ];
    }
}
