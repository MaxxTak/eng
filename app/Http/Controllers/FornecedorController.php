<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\CategoriasFornecedor;
use App\Models\Pessoa;
use App\Models\Conta;
use App\Models\Endereco;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
   /*     $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])
            ->get();
        foreach ($retornos as $retorno){
            $end = $db->table('enderecos')
                ->where('id', '=', $retorno->endereco_id)
                ->first();

            $cont = $db->table('contas')
                ->where('id', '=', $retorno->conta_id)
                ->first();
            $retorno->endereco = $end;
            $retorno->conta = $cont;
        }

        return $retornos;*/
        $db = \DB::connection('centralizador');
        $retorno = Pessoa::where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->with('endereco','conta')->get();
        foreach ($retorno as $r){
            $categorias = CategoriasFornecedor::where('fornecedor_id',$r->id)->get();
            $arr =[];
            if(count($categorias)>0){
                foreach ($categorias as $categoria){
                    $arr[] = $db->table('categorias')->where('id',$categoria->centralizador_categoria_id)->first();
                }
                $arr = $this->arrayToObject($arr);
            }
            $r->Categoria = $arr;
        }
        return $retorno;
    }

    public function arrayToObject($array){
        $object = new \stdClass();
        foreach ($array as $key => $value)
        {
            $object->$key = $value;
        }
        return $object;
    }


    public function retornarCategorias(){
        $db = \DB::connection('centralizador');
        $retornos = $db->table('categorias')->get();
        return $retornos;
    }

    public function retornarFornecedor(Request $request){
        $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])
            ->Where('cnpjcpf','LIKE','%'.$request->get('fornecedor').'%')
            ->orWhere('nome','LIKE','%'.$request->get('fornecedor').'%')
            ->orWhere('razao_social','LIKE','%'.$request->get('fornecedor').'%')
            ->orWhere('email','LIKE','%'.$request->get('fornecedor').'%')
            ->orWhere('rg','LIKE','%'.$request->get('fornecedor').'%')
            ->orWhere('email_secundario','LIKE','%'.$request->get('fornecedor').'%')
            ->get();

        if(count($retornos)> 0) {
            foreach ($retornos as $retorno){
                $end = $db->table('enderecos')
                    ->where('id', '=', $retorno->endereco_id)
                    ->first();

                $cont = $db->table('contas')
                    ->where('id', '=', $retorno->conta_id)
                    ->first();

                $retorno->endereco = $end;
                $retorno->conta = $cont;
            }

            return Response::json($retornos, 200);
        }
        return Response::json($retornos, 404);
    }


    public function retornarCnpj(Request $request){
        $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->where('cnpjcpf',$request->get('cnpjcpf'))
            ->first();
        if(!is_null($retornos)){
            $end = $db->table('enderecos')
                ->where('id', '=', $retornos->endereco_id)
                ->first();

            $cont = $db->table('contas')
                ->where('id', '=', $retornos->conta_id)
                ->first();

            $retornos->endereco = $end;
            $retornos->conta = $cont;

            return Response::json($retornos, 200);
        }



       // return $retornos;
        return Response::json($retornos, 404);

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
        $fk_centralizador = $request->get('fk_fornecedor_centralizar');
        $db = \DB::connection('centralizador');
        if(is_null($fk_centralizador)){

            $db->beginTransaction();
            try{
                $retEnd = $db->table('enderecos')->insert([
                    'cep' => $request->input('cep'),
                    'endereco' => $request->input('endereco'),
                    'numero'=> $request->input('numero'),
                    'complemento'=> $request->input('complemento'),
                    'bairro'=> $request->input('bairro'),
                    'uf'=> $request->input('uf'),
                    'municipio'=> $request->input('municipio')
                ]);



                $retConta = $db->table('contas')->insert([
                    'banco' => $request->get('banco'),
                    'agencia' => $request->get('agencia'),
                    'conta' => $request->get('conta'),
                    'tipo_conta' => $request->get('tipo_conta'),
                    'observacao' => $request->get('observacao')
                ]);


                if(($retConta)&&($retEnd)){
                    $endereco = $db->table('enderecos')->max('id');
                    $conta = $db->table('contas')->max('id');
                    $pes = $db->table('pessoas')->insert([
                        'tipo'=> Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR],
                        'nome'=> $request->input('nome'),
                        'razao_social'=> !is_null($request->input('razaoSocial'))? $request->input('razaoSocial') : $request->input('razao_social'),
                        'cnpjcpf'=> $request->input('cnpjcpf'),
                        'rg'=> $request->input('rg'),
                        'telefone1'=> $request->input('telefone1'),
                        'telefone2'=> $request->input('telefone2'),
                        'telefone3'=> $request->input('telefone3'),
                        'email'=> $request->input('email'),
                        'cargo'=> $request->input('cargo'),
                        'setor'=> $request->input('setor'),
                        'endereco_id' => $endereco,
                        'telefone4' => $request->get('telefone4'),
                        'email_secundario' => $request->get('email_secundario'),
                        'conta_id' => $conta
                    ]);
                    $fk_centralizador = $db->table('pessoas')->max('id');//Pessoa::where('id',$pessoa->id)->with('endereco','conta')->first();

                  /*  $categorias = new Categoria([
                        'descricao'=> $request->get('descricao'),
                        'isSelected' => $request->get('isSelected'),
                    ]);
                    $categorias->save();
                    $categorias->fornecedor_id = $fk_centralizador;
                    $categorias->save();
                    $retCat = $db->table('categorias')->insert([
                                'descricao' => $request->get('banco'),
                                'isSelected' => $request->get('agencia'),
                                'fornecedor_id' => $fk_centralizador,
                            ]);*/

                    if($pes){

                        //=============== LOCAL =======================
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

                        $conta = new Conta([
                            'banco' => $request->get('banco'),
                            'agencia' => $request->get('agencia'),
                            'conta' => $request->get('conta'),
                            'tipo_conta' => $request->get('tipo_conta')
                        ]);

                        $conta->save();

                        $pessoa = new Pessoa([
                            'tipo'=> Pessoa::PESSOA_TIPO_ID[Pessoa:: FORNECEDOR],
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
                        $pessoa->telefone4 = $request->get('telefone4');
                        $pessoa->email_secundario = $request->get('emailSecundario');
                        $pessoa->conta_id = $conta->id;
                        $pessoa->fk_fornecedor_centralizar = $fk_centralizador;
                        $pessoa->save();

                        $categorias = $request->has('Categoria') ? $request->get('Categoria') : $request->has('categorias') ? $request->get('categorias') : $request->has('Categorias')? $request->get('Categorias') : array();
                        foreach ($categorias as $categoria){
                            $cat_P = new CategoriasFornecedor([
                                'fornecedor_id' => $pessoa->id,
                                'centralizador_categoria_id' => $categoria['id']
                            ]);
                            $cat_P->save();
                        }

                        //=============== END LOCAL =======================

                     /*   $id = $db->table('pessoas')->max('id');;//Pessoa::where('id',$pessoa->id)->with('endereco','conta')->first();
                        $retorno = $db->table('pessoas')
                            ->where('id', '=', $id)
                            ->first();

                        $end = $db->table('enderecos')
                            ->where('id', '=', $endereco)
                            ->first();

                        $cont = $db->table('contas')
                            ->where('id', '=', $conta)
                            ->first();

                        $retorno->endereco = $end;
                        $retorno->conta = $cont;*/
                        $retorno = Pessoa::where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->with('endereco','conta')->where('id',$pessoa->id)->first();
                        $categorias = CategoriasFornecedor::where('fornecedor_id',$retorno->id)->get();
                        $arr =[];
                        if(count($categorias)>0){
                            foreach ($categorias as $categoria){
                                $arr[] = $db->table('categorias')->where('id',$categoria->centralizador_categoria_id)->first();
                            }
                            $arr = $this->arrayToObject($arr);
                        }
                        $retorno->Categoria = $arr;
                        $db->commit();

                        return Response::json( $retorno, 200);
                    }

                }
            }catch (\Exception $exception){
                $db->rollBack();
                //return Redirect::back()->withErrors(['msg', 'Erro ao inserir']);

                return Response::json(array(
                    'code'      =>  404,
                    'message'   =>  "Erro ao inserir"
                ), 404);
            }

        }else{
            $p_aux = Pessoa::where('cnpjcpf', $request->input('cnpjcpf'))->first();
            if(!is_null($p_aux)){
                return Response::json(array(
                    'code'      =>  400,
                    'retorno'   =>  $p_aux
                ), 400);
            }
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

             $conta = new Conta([
                'banco' => $request->get('banco'),
                'agencia' => $request->get('agencia'),
                'conta' => $request->get('conta'),
                'tipo_conta' => $request->get('tipo_conta')
            ]);

            $conta->save();

             $pessoa = new Pessoa([
                 'tipo'=> Pessoa::PESSOA_TIPO_ID[Pessoa:: FORNECEDOR],
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
             $pessoa->telefone4 = $request->get('telefone4');
             $pessoa->email_secundario = $request->get('emailSecundario');
             $pessoa->conta_id = $conta->id;
             $pessoa->fk_fornecedor_centralizar = $fk_centralizador;
             $pessoa->save();

            $retorno = Pessoa::where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->with('endereco','conta')->where('id',$pessoa->id)->first();
            $categorias = CategoriasFornecedor::where('fornecedor_id',$retorno->id)->get();
            $arr =[];
            if(count($categorias)>0){
                foreach ($categorias as $categoria){
                    $arr[] = $db->table('categorias')->where('id',$categoria->centralizador_categoria_id)->first();
                }
                $arr = $this->arrayToObject($arr);
            }
            $retorno->Categoria = $arr;


            return Response::json( $retorno, 200);
        }

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
    /*    $db = \DB::connection('centralizador');

        $retornos = $db->table('pessoas')->where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->where('id',$id)
            ->first();

        $end = $db->table('enderecos')
            ->where('id', '=', $retornos->endereco_id)
            ->first();
        $retornos->endereco = $end;

        $cont = $db->table('contas')
            ->where('id', '=', $retornos->conta_id)
            ->first();

        $retornos->conta = $cont;

        return Response::json(array(
            'code'      =>  200,
            'retorno'   =>  $retornos
        ), 200);*/
        $db = \DB::connection('centralizador');
        $retorno = Pessoa::where('tipo',Pessoa::PESSOA_TIPO_ID[Pessoa::FORNECEDOR])->with('endereco','conta')->where('id',$id)->first();
        $categorias = CategoriasFornecedor::where('fornecedor_id',$retorno->id)->get();
        $arr =[];
        if(count($categorias)>0){
            foreach ($categorias as $categoria){
                $arr[] = $db->table('categorias')->where('id',$categoria->centralizador_categoria_id)->first();
            }
            $arr = $this->arrayToObject($arr);
        }
        $retorno->Categoria = $arr;
        return $retorno;
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

        $cont = $db->table('contas')
            ->where('id', '=', $retorno->conta_id)
            ->first();


        $pessoa = new \stdClass() ;//Pessoa::find($id);
        $endereco = new \stdClass() ;//Endereco::find($pessoa->endereco_id);
        $conta = new \stdClass();


        if($request->has('banco'))
            $conta->banco = $request->input('banco');
        if($request->has('agencia'))
            $conta->agencia = $request->input('agencia');
        if($request->has('conta'))
            $conta->conta = $request->input('conta');
        if($request->has('tipo_conta'))
            $conta->tipo_conta = $request->input('tipo_conta');
        if($request->has('observacao'))
            $conta->observacao = $request->input('observacao');

        $conta = $this->stdArray($conta);
        $retConta = !is_null($cont)? $db->table('contas')->where('id',$cont->id)->update((array)$conta) : 0;


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

        //$endereco->save();


        $endereco = $this->stdArray($endereco);
        $retEnd = !is_null($end)? $db->table('enderecos')->where('id',$end->id)->update((array)$endereco) : 0;

        if($request->has('nome'))
            $pessoa->nome= $request->input('nome');
        if(($request->has('razaoSocial'))||($request->has('razao_social')))
            $pessoa->razao_social= !is_null($request->input('razaoSocial'))? $request->input('razaoSocial') : $request->input('razao_social');
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


        $pessoa = $this->stdArray($pessoa);
        $retPes = $db->table('pessoas')->where('id',$id)->update((array)$pessoa);


        //$pessoa->save();
        $retorno = $db->table('pessoas')
            ->where('id', '=', $id)
            ->first();

        $end = $db->table('enderecos')
            ->where('id', '=', $retorno->endereco_id)
            ->first();

        $cont = $db->table('contas')
            ->where('id', '=', $retorno->conta_id)
            ->first();

        $retorno->endereco = $end;
        $retorno->conta = $cont;

        return Response::json($retorno, 200);
        //return $retorno;
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
