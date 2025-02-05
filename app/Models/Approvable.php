<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvable extends Model
{
    use HasFactory;

    protected $fillable = [
        'approver_id',
        'status',
        'approvable_type',
        'approvable_id',
        'note',
        'request_date',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function approvable()
    {
        return $this->morphTo();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
