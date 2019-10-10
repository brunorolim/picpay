<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Transaction extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'transaction';

    protected $fillable = [
        'payee_id',
        'payer_id',
        'value'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'payee_id' => 'required',
        'payer_id' => 'required',
        'value' => 'required|numeric',
    ];
}
