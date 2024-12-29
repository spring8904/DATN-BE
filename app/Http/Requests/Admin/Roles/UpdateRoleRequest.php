<?php

namespace App\Http\Requests\Admin\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class UpdateRoleRequest extends FormRequest
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
        $id = $this->route('role');

        $role = Role::query()->findOrFail($id);

        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|in:web,api',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Vai trò không được để trống',
            'name.unique' => 'Vai trò đã tồn tại',
            'guard_name.required' => 'Guard name không được để trống',
            'guard_name.in' => 'Guard name không hợp lệ',
            'permissions.array' => 'Quyền phải là một mảng.',
            'permissions.*.exists' => 'Một hoặc nhiều quyền không tồn tại.',
        ];
    }
}
