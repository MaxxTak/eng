<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoObra extends Model
{
    //
    protected $table = 'tipos_obra';
    protected $fillable=[
        'descricao'
    ];
}
