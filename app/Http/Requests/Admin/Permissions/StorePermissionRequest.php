<?php

namespace App\Http\Requests\Admin\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
            'name' => 'required|unique:permissions,name',
            // 'guard_name' => 'required',
            // 'route' => 'required|string|unique:permissions,route',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên quyền không được để trống',
            'name.unique' => 'Tên quyền quyền đã tồn tại',
            // 'route.required' => 'Đường dẫn không được để trống',
            // 'route.unique' => 'Đường dẫn đã tồn tại',
            'description.required' => 'Nhập mô tả của quyền này',
            // 'guard_name.required' => 'Guard name  không được để trống',
        ];
    }
}
