<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'user';

    protected $fillable = [
        'cpf',
        'email',
        'full_name',
        'password',
        'phone_number'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'cpf' => 'required|unique:user,cpf',
        'email' => 'required|email|unique:user,email',
        'full_name' => 'required',
        'password' => 'required',
        'phone_number' => 'required',
    ];

    public function consumer()
    {
        return $this->hasOne(Consumer::class, "user_id");
    }

    public function seller()
    {
        return $this->hasOne(Seller::class, "user_id");
    }
}
