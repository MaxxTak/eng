<?php

namespace App\Http\Controllers;

use App\Model\Pessoa;
use App\Models\Conta;
use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return Contato::where('pessoa_id',$request->has('id_pessoa') ? $request->get('id_pessoa') : 0)->get();
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
        $contato = new Contato([
            'descricao'=> $request->get('descricao'),
            'conteudo'=> $request->get('conteudo'),
            'pessoa_id'=> $request->get('id_pessoa')
        ]);
        $contato->save();
        $contato = Contato::find($contato->id);
        return $contato;
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

        return Contato::where('pessoa_id',$id)->get();
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
        $contato = Contato::find($id);
        if($request->has('descricao'))
            $contato->descricao = $request->get('descricao');
        if($request->has('conteudo'))
            $contato->conteudo = $request->get('conteudo');
        if($request->has('id_pessoa'))
            $contato->pessoa_id = $request->get('id_pessoa');
        $contato->save();
        return $contato;
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
        $contato = Contato::find($id);
        $contato->delete();

        return Response::json(array(
            'code'      =>  200,
            'retorno'   =>  "deletado com sucesso"
        ), 200);
    }
}
