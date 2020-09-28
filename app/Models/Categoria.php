<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $connection = 'centralizador';
    protected $fillable=[
        'descricao',
        'isSelected',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(\App\Models\Pessoa::class,'fornecedor_id','id');
    }
}
