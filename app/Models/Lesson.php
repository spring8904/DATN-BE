<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'slug',
        'duration',
        'content',
        'playback_id',
        'is_free_preview',
        'order',
        'lessonable_type',
        'lessonable_id',
    ];

    public $attributes = [
        'duration' => 0,
        'is_free_preview' => 0,
        'order' => 0,
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
