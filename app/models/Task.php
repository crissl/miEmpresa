<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $connection = 'pgsql-ignug';

    protected $fillable = [
        'description',
        'percentage_advance',
        'observations'
    ];

    public function taskable()
    {
        return $this->morphTo();
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function type()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
