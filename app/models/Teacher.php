<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $connection = 'pgsql-ignug';
    protected $fillable = [
        'date'
    ];

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendanceable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
