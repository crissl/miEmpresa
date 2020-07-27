<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $connection = 'pgsql-ignug';
    public $timestamps = false;
    protected $fillable = [
        'code',
        'name',
        'state',
    ];
}
