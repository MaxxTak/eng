<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    //
    use SoftDeletes;

    protected $connection = 'company';

    const CLIENTE= "Cliente";
    const FUNCIONARIO = "Funcionário";
    const FORNECEDOR = "Fornecedor";
    const VENDEDOR = "Vendedor";

    const PESSOA_TIPO = [
        1 => self::CLIENTE,
        2 => self::FUNCIONARIO,
        3 => self::FORNECEDOR,
        4 => self::VENDEDOR
    ];

    const PESSOA_TIPO_ID = [
        self::CLIENTE => 1,
        self::FUNCIONARIO => 2,
        self::FORNECEDOR => 3,
        self::VENDEDOR => 4
    ];

    protected $fillable = [
        'tipo',
        'nome',
        'razao_social',
        'cnpjcpf',
        'rg',
        'telefone1',
        'telefone2',
        'telefone3',
        'email',
        'cargo',
        'setor',
        'endereco_id'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
    public function endereco()
    {
        return $this->belongsTo(\App\Models\Endereco::class);
    }

    public function conta()
    {
        return $this->belongsTo(\App\Models\Conta::class);
    }

    public function categoria()
    {
        return $this->hasMany(\App\Models\Categoria::class,'id','fornecedor_id');
    }
}

