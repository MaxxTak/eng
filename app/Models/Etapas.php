<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etapas extends Model
{
    //
    use SoftDeletes;
    protected $table = 'etapas_obra';
    protected $fillable=[
        'descricao'
    ];
}
