<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workday extends Model
{
    protected $connection = 'pgsql-ignug';

    protected $fillable = [
        'start_time',
        'end_time',
        'description',
        'duration',
        'observations',
    ];

    protected $casts = [
        'start_time' => 'time:H:i:s',
        'end_time' => 'time:H:i:s',
    ];

    public function workdayable()
    {
        return $this->morphTo();
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
