<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'pgsql-authentication';
    protected $fillable = [
        'user_name', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function rules()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'user_name.unique' => 'The user_name has already been taken.',
        ];
    }


    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Teacher::class);
    }
}
