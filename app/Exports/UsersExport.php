<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\Permission\Models\Role;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $role;

    public function __construct($role = 'member')
    {
        $this->role = $role;
    }
    public function collection()
    {
        return User::whereHas('roles', function($query){
            $query->where('name',$this->role);
        })->with('profile')->get();
    }

    public function headings(): array {
        return [
            'STT',
            'Mã người dùng',
            'Họ và tên',
            'Email',
            'Số điện thoại',
            'Địa chỉ',
            'Kinh nghiệm',
            'Thời gian xác minh email',
            'Trạng Thái tài khoản',
            'Vai Trò',
            'Ngày Tham Gia',
        ];
    }

    public function map($user): array {
        static $index = 1;

        return [
            $index++,
            $user->code,
            $user->name,
            $user->email,
            $user->profile->phone ?? 'Chưa có thông tin',
            $user->profile->address ?? 'Chưa có thông tin',
            $user->experience ?? 'Chưa có thông tin',
            $user->email_verified_at ?? 'Chưa xác minh',
            $user->status === 'active' ? 'Hoạt động' : ( $user->status === 'inactive' ? 'Không hoạt động' : 'Bị khóa'),
            $this->role,
            $user->created_at,
        ];
    }
}
