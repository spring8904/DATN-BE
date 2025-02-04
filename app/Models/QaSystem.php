<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QaSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'question',
        'options',
        'answer_type',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
