<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Seller extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'seller';

    protected $fillable = [
        'user_id',
        'cnpj',
        'fantasy_name',
        'social_name',
        'username'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'user_id' => 'required|unique:seller,user_id',
        'cnpj' => 'required',
        'fantasy_name' => 'required',
        'social_name' => 'required',
        'username' => 'required|unique:seller,username|unique:consumer,username'
    ];
}