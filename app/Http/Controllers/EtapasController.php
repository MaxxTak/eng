<?php

namespace App\Http\Controllers;

use App\Models\Etapas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EtapasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Etapas::all();
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
        $etapas = new Etapas([
           'descricao' => $request->get('descricao')
        ]);
        $etapas->save();
        return Etapas::find($etapas->id);
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
        return Etapas::find($id);
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
        $etapas = Etapas::find($id);
        if($request->has('descricao'))
            $etapas->descricao = $request->get('descricao');
        $etapas->save();
        return $etapas;
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
        $etapas = Etapas::find($id);
        $etapas->delete();
        return Response::json(array(
            'code'      =>  200,
            'retorno'   =>  "deletado com sucesso"
        ), 200);
    }
}
