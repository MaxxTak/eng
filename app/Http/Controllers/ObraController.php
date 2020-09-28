<?php

namespace App\Http\Controllers;

use App\Models\Cobranca;
use App\Models\Endereco;
use App\Models\Obra;
use Illuminate\Http\Request;

class ObraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $retorno = Obra::with('endereco','pessoa','tipo','forma')->get();
        foreach ($retorno as $ret){
            $ret->cobranca = Cobranca::where('id',$ret->cobranca_id)->with('endereco')->first();
        }
        //

        return $retorno;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($request);
        $endereco = new Endereco([
            'cep' => $request->input('cep'),
            'endereco' => $request->input('endereco'),
            'numero'=> $request->input('numero'),
            'complemento'=> $request->input('complemento'),
            'bairro'=> $request->input('bairro'),
            'uf'=> $request->input('uf'),
            'municipio'=> $request->input('municipio'),
        ]);
        $endereco->save();

        $endereco2 = new Endereco([
            'cep' => $request->input('cep_cobranca'),
            'endereco' => $request->input('endereco_cobranca'),
            'numero'=> $request->input('numero_cobranca'),
            'complemento'=> $request->input('complemento_cobranca'),
            'bairro'=> $request->input('bairro_cobranca'),
            'uf'=> $request->input('uf_cobranca'),
            'municipio'=> $request->input('municipio_cobranca'),
        ]);
        $endereco2->save();

        $cobranca = new Cobranca([
            'tipo_pagamento' => $request->get('tipo_pagamento'),
            'responsavel'=> $request->get('responsavel_cobranca'),
            'email'=> $request->get('email_cobranca'),
            'endereco_id' => $endereco2->id
        ]);
        $cobranca->save();

        $obra = new Obra([
            'pessoa_id'=> $request->input('id_pessoa'),
            'tipo_obra_id'=> $request->input('id_tipo_obra'),
            'forma_pagamento_id'=> $request->input('id_forma_pagamento'),
            'descricao'=> $request->input('descricao'),
            'observacoes_pagamento'=> $request->input('observacoes_pagamento'),
            'endereco_id' => $endereco->id,
            'cobranca_id' => $cobranca->id,
            'tipo_administracao' => $request->get('tipo_administracao'),
            'valor_administracao'=> $request->get('valor_administracao'),
            'prazo_previsto' => $request->get('prazo_previsto'),
            'tipo_prazo_previsto' => $request->get('tipo_prazo_previsto'),
            'inicio'=> $request->get('data_inicio'),
            'tipo_cobranca_administracao'=> $request->get('tipo_cobranca_administracao'),
            'dia_1'=> $request->get('dia_1'),
            'dia_2'=> $request->get('dia_2'),
            'envio_pagamento'=> $request->get('envio_pagamento'),
            'dia_semana_envio_pagamento'=> $request->get('dia_semana_envio_pagamento'),
            'dia_mes_envio_pagamento' => $request->get('dia_mes_envio_pagamento')
        ]);
        $obra->save();

        $retorno = Obra::where('id',$obra->id)->with('endereco')->first();
        $retorno->cobranca = Cobranca::where('id',$cobranca->id)->with('endereco')->first();

        return $retorno;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $obra = Obra::find($id);
        $cobranca = Cobranca::find($obra->cobranca_id);
        $endereco = Endereco::find($obra->endereco_id);
        $endereco2 = Endereco::find($cobranca->endereco_id);

        if($request->has('cep'))
           $endereco->cep= $request->input('cep');
        if($request->has('endereco'))
            $endereco->endereco =$request->input('endereco');
        if($request->has('numero'))
            $endereco->numero= $request->input('numero');
        if($request->has('complemento'))
            $endereco->complemento= $request->input('complemento');
        if($request->has('bairro'))
            $endereco->bairro= $request->input('bairro');
        if($request->has('uf'))
            $endereco->uf =$request->input('uf');
        if($request->has('municipio'))
            $endereco->municipio= $request->input('municipio');

        $endereco->save();

        if($request->has('cep_cobranca'))
            $endereco2->cep = $request->input('cep_cobranca');
        if($request->has('endereco_cobranca'))
            $endereco2->endereco = $request->input('endereco_cobranca');
        if($request->has('numero_cobranca'))
            $endereco2->numero =$request->input('numero_cobranca');
        if($request->has('complemento_cobranca'))
            $endereco2->complemento =$request->input('complemento_cobranca');
        if($request->has('bairro_cobranca'))
            $endereco2->bairro =$request->input('bairro_cobranca');
        if($request->has('uf_cobranca'))
            $endereco2->uf =$request->input('uf_cobranca');
        if($request->has('municipio_cobranca'))
            $endereco2->municipio =$request->input('municipio_cobranca');

        $endereco2->save();

        if($request->has('tipo_pagamento'))
            $cobranca->tipo_pagamento = $request->get('tipo_pagamento');
        if($request->has('responsavel_cobranca'))
            $cobranca->responsavel =$request->get('responsavel_cobranca');
        if($request->has('email_cobranca'))
            $cobranca->email =$request->get('email_cobranca');


        $cobranca->save();

        if($request->has('id_pessoa'))
            $obra->pessoa_id = $request->input('id_pessoa');
        if($request->has('id_tipo_obra'))
            $obra->tipo_obra_id = $request->input('id_tipo_obra');
        if($request->has('id_forma_pagamento'))
            $obra->forma_pagamento_id = $request->input('id_forma_pagamento');
        if($request->has('descricao'))
            $obra->descricao = $request->input('descricao');
        if($request->has('observacoes_pagamento'))
            $obra->observacoes_pagamento = $request->input('observacoes_pagamento');
        if($request->has('tipo_administracao'))
            $obra->tipo_administracao =  $request->get('tipo_administracao');
        if($request->has('valor_administracao'))
            $obra->valor_administracao = $request->get('valor_administracao');
        if($request->has('prazo_previsto'))
            $obra->prazo_previsto =  $request->get('prazo_previsto');
        if($request->has('tipo_prazo_previsto'))
            $obra->tipo_prazo_previsto =  $request->get('tipo_prazo_previsto');
        if($request->has('data_inicio'))
            $obra->inicio = $request->get('data_inicio');
        if($request->has('tipo_cobranca_administracao'))
            $obra->tipo_cobranca_administracao = $request->get('tipo_cobranca_administracao');
        if($request->has('dia_1'))
            $obra->dia_1 = $request->get('dia_1');
        if($request->has('dia_2'))
            $obra->dia_2 = $request->get('dia_2');

        if($request->has('envio_pagamento'))
            $obra->envio_pagamento = $request->get('envio_pagamento');
        if($request->has('dia_semana_envio_pagamento'))
            $obra->dia_semana_envio_pagamento= $request->get('dia_semana_envio_pagamento');
        if($request->has('dia_mes_envio_pagamento'))
            $obra->dia_mes_envio_pagamento = $request->get('dia_mes_envio_pagamento');

        $obra->save();

        $retorno = Obra::where('id',$obra->id)->with('endereco')->first();
        $retorno->cobranca = Cobranca::where('id',$cobranca->id)->with('endereco')->first();

        return $retorno;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
