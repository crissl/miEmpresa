<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $connection = 'pgsql-ignug';
    protected $fillable = [
        'code',
        'parent_code_id',
        'name',
        'type',
        'icon'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function tasks()
    {
        return $this->hasMany(Catalogue::class, 'parent_code_id');
    }
}
