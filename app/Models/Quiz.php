<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'question',
        'image',
        'answer_type',
        'description',
=======
        'lesson_id',
        'question',
        'image',
        'answer_type',
        'description'
>>>>>>> 7125a6f86ab6e7e6ae2bc721c068938d4a199251
    ];
}
