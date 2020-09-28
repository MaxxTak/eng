<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    //
    protected $fillable=[
        'banco',
        'agencia',
        'conta',
        'tipo_conta'
    ];

}
