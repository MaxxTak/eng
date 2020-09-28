<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriasFornecedor extends Model
{
    //
    protected $table = 'categorias_fornecedor';
    protected $fillable=[
        'fornecedor_id',
        'centralizador_categoria_id'
    ];
}
