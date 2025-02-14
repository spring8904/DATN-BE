<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'course_id',
        'user_id',
        'total_amount',
        'retained_amount',
        'release_date',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
