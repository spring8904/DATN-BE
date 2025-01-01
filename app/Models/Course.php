<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory , SoftDeletes;

    const LEVEL_BEGINNER = 'beginner';
    const LEVEL_INTERMEDIATE =
    'intermediate';
    const LEVEL_ADVANCED = 'advanced';

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'thumbnail',
        'price',
        'price_sale',
        'description',
        'duration',
        'level',
        'total_student',
        'requirement',
        'benefits',
        'qa',
        'status',
        'accepted',
    ];

    protected $casts = [
        'requirement' => 'array',
        'benefits' => 'array',
        'qa' => 'array',
    ];

    public $attributes = [
        'status' => self::STATUS_DRAFT,
        'total_student' => 0,
        'requirement' => '[]',
        'benefits' => '[]',
        'qa' => '[]',
    ];
}
