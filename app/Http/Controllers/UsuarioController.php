<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Pessoa;
use App\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $retorno = Pessoa::where('tipo', Pessoa::PESSOA_TIPO_ID[Pessoa::FUNCIONARIO])->with('user','endereco')->get();
        return $retorno;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = User::create(
            [
                'name' => $request->input('nome'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

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
        $pessoa = new Pessoa([
            'tipo'=> Pessoa::PESSOA_TIPO_ID[Pessoa::FUNCIONARIO],
            'nome'=> $request->input('nome'),
            'razao_social'=> $request->input('razaoSocial'),
            'cnpjcpf'=> $request->input('cnpjcpf'),
            'rg'=> $request->input('rg'),
            'telefone1'=> $request->input('telefone1'),
            'telefone2'=> $request->input('telefone2'),
            'telefone3'=> $request->input('telefone3'),
            'email'=> $request->input('email'),
            'cargo'=> $request->input('cargo'),
            'setor'=> $request->input('setor'),
            'endereco_id' => $endereco->id
        ]);

        $pessoa->save();
        $pessoa->user_id = $user->id;
        $pessoa->save();

        $retorno = Pessoa::where('id',$pessoa->id)->with('user','endereco')->first();

        return $retorno;
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
        $pessoa = Pessoa::find($id);
        $endereco = Endereco::find($pessoa->endereco_id);
        if($request->has('cep'))
            $endereco->cep = $request->input('cep');
        if($request->has('endereco'))
            $endereco->endereco = $request->input('endereco');
        if($request->has('numero'))
            $endereco->numero= $request->input('numero');
        if($request->has('complemento'))
            $endereco->complemento= $request->input('complemento');
        if($request->has('bairro'))
            $endereco->bairro= $request->input('bairro');
        if($request->has('uf'))
            $endereco->uf= $request->input('uf');
        if($request->has('municipio'))
            $endereco->municipio = $request->input('municipio');

        $endereco->save();

        if($request->has('nome'))
            $pessoa->nome= $request->input('nome');
        if($request->has('razaoSocial'))
            $pessoa->razao_social= $request->input('razaoSocial');
        if($request->has('cnpjcpf'))
            $pessoa->cnpjcpf= $request->input('cnpjcpf');
        if($request->has('rg'))
            $pessoa->rg= $request->input('rg');
        if($request->has('telefone1'))
            $pessoa->telefone1= $request->input('telefone1');
        if($request->has('telefone2'))
            $pessoa->telefone2= $request->input('telefone2');
        if($request->has('telefone3'))
            $pessoa->telefone3= $request->input('telefone3');
        if($request->has('email'))
            $pessoa->email =$request->input('email');
        if($request->has('cargo'))
            $pessoa->cargo =$request->input('cargo');
        if($request->has('setor'))
            $pessoa->setor =$request->input('setor');


        $pessoa->save();

        $user = User::find($pessoa->user_id);

        if($request->has('nome'))
            $user->name = $request->input('nome');
        if($request->has('email'))
            $user->email = $request->input('email');
        if($request->has('password'))
            $user->password = bcrypt($request->input('password'));

        $user->save();
        $retorno = Pessoa::where('id',$pessoa->id)->with('user','endereco')->first();
        return $retorno;
    }
}
