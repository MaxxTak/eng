<?php

namespace App\Http\Controllers;


use App\Models\Endereco;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class VendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      /*  $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR])
            ->get();
        foreach ($retornos as $retorno){
            $end = $db->table('enderecos')
                ->where('id', '=', $retorno->endereco_id)
                ->first();
            $retorno->endereco = $end;
        }*/
        $retornos = Pessoa::with('endereco')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR])->get();


        return Response::json($retornos, 200);
        //return $retornos;

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
        //
        \DB::beginTransaction();
        $retEnd = new Endereco([
            'cep' => $request->input('cep'),
            'endereco' => $request->input('endereco'),
            'numero'=> $request->input('numero'),
            'complemento'=> $request->input('complemento'),
            'bairro'=> $request->input('bairro'),
            'uf'=> $request->input('uf'),
            'municipio'=> $request->input('municipio')
        ]);

        $retEnd->save();

        if(($retEnd)){
            $endereco = $retEnd->id;//$db->table('enderecos')->max('id');

            $pes = new Pessoa([
                'tipo'=> Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR],
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
                'endereco_id' => $endereco
            ]);
            $pes->save();
            $pes->telefone4 = $request->get('telefone4');
            $pes->email_secundario = $request->get('email_secundario');
            $pes->fornecedor_id = $request->get('id_pessoa');
            $pes->save();
            if($pes){
                $retorno = Pessoa::with('endereco')->where('id', $pes->id)->first();
                \DB::commit();
                return Response::json($retorno, 200);
            }

        }


        \DB::rollBack();
        return Response::json(array(
            'retorno'   =>  "Erro ao inserir"
        ), 400);
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
      /*  $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR])->where('fornecedor_id',$id)
            ->get();

        if(count($retornos)>0){
            foreach ($retornos as $retorno){
                $end = $db->table('enderecos')
                    ->where('id', '=', $retorno->endereco_id)
                    ->first();
                $retorno->endereco = $end;
            }
        }*/
      $retornos = Pessoa::with('endereco')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR])->where('fornecedor_id',$id)->get();



        return Response::json($retornos, 200);
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


    function stdArray($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->stdArray($value);
                }
                if ($value instanceof \stdClass) {
                    $array[$key] = $this->stdArray((array)$value);
                }
            }
        }
        if ($array instanceof \stdClass) {
            return $this->stdArray((array)$array);
        }
        return $array;
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

        $db = \DB::connection('centralizador');


        $retorno = $db->table('pessoas')
            ->where('id', '=', $id)
            ->first();

        $end = $db->table('enderecos')
            ->where('id', '=', $retorno->endereco_id)
            ->first();

        $pessoa = Pessoa::find($id);// new \stdClass() ;
        $endereco = Endereco::find($pessoa->endereco_id); //new \stdClass() ;//


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


        //$endereco = $this->stdArray($endereco);
        //$retEnd = !is_null($end)? $db->table('enderecos')->where('id',$end->id)->update((array)$endereco) : 0;

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
        if($request->has('telefone4'))
            $pessoa->telefone4 = $request->get('telefone4');
        if($request->has('email_secundario'))
            $pessoa->email_secundario = $request->get('email_secundario');


        //$pessoa = $this->stdArray($pessoa);
        //$retPes = $db->table('pessoas')->where('id',$id)->update((array)$pessoa);



    /*    $retorno = $db->table('pessoas')
            ->where('id', '=', $id)
            ->first();

        $end = $db->table('enderecos')
            ->where('id', '=', $retorno->endereco_id)
            ->first();


        $retorno->endereco = $end;*/
        $pessoa->save();
        $retorno = Pessoa::with('endereco')->where('id',$id)->first();

        return Response::json($retorno, 200);
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
        $pessoa = Pessoa::find($id);
        if(!is_null($pessoa)){
            if($pessoa->tipo == Pessoa::PESSOA_TIPO_ID[Pessoa::VENDEDOR])
                $pessoa->delete();
        }


        return Response::json(array(
            'code'      =>  200,
            'retorno'   =>  "Ok"
        ), 200);
    }
}
