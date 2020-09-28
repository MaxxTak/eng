<?php

namespace App\Http\Controllers;

class DatabaseController extends Controller
{
    //
    public function getDatabase($codEmpresa) {
        $db = \DB::connection('centralizador');

        return $db->table('empresa')
            ->select('emp_banco', 'emp_status')
            ->where('emp_codigo', '=', $codEmpresa )
            ->first();
    }

    public function getDatabasePorNome($empresa) {
        return \DB::connection('centralizador')
            ->table('empresa')
            ->select('emp_banco','emp_codigo', 'emp_razao_social', 'emp_status')
            ->where('emp_nome', '=', $empresa )
            ->first();
    }

    public function buscarTodosAtivos() {
        return \DB::connection('centralizador')
            ->table('empresa')
            ->where('emp_status', '<>', '0')
            ->get();
    }
}
