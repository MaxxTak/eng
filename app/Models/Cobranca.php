<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobranca extends Model
{
    //
    protected $fillable=[
        'tipo_pagamento',
        'responsavel',
        'email',
        'endereco_id'
    ];

    public function endereco()
    {
        return $this->belongsTo(\App\Models\Endereco::class);
    }
}
