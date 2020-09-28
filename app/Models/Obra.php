<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    //
    protected $fillable =[
        'pessoa_id',
        'tipo_obra_id',
        'forma_pagamento_id',
        'descricao',
        'observacoes_pagamento',
        'endereco_id',
        'cobranca_id',
        'tipo_administracao',
        'valor_administracao',
        'prazo_previsto',
        'inicio',
        'tipo_prazo_previsto',
        'tipo_cobranca_administracao',
        'dia_1',
        'dia_2',
        'envio_pagamento',
        'dia_semana_envio_pagamento',
        'dia_mes_envio_pagamento'

    ];

    public function endereco()
    {
        return $this->belongsTo(\App\Models\Endereco::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(\App\Models\Pessoa::class);
    }

    public function tipo()
    {
        return $this->belongsTo(\App\Models\TipoObra::class,'tipo_obra_id','id');
    }

    public function forma()
    {
        return $this->belongsTo(\App\Models\FormaPagamento::class,'forma_pagamento_id','id');
    }

}

