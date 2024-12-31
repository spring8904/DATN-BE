<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'name',
        'degree',
        'major',
        'start_date',
        'end_date',
        'certificates',
        'qa_systems'
    ];
}
