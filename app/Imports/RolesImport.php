<?php

namespace App\Imports;

use App\Models\Role;
use App\Traits\LoggableTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RolesImport implements ToModel, WithHeadingRow
{
    use LoggableTrait;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            return new \Spatie\Permission\Models\Role([
                'name' => $row['name'],
                'guard_name' => $row['guard_name'],
                'description' => $row['description'],
            ]);
        } catch (\Exception $e) {
            $this->logError($e);

            return null;
        }
    }
}
