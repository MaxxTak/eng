<?php

namespace App\Http\Middleware;

use App\Exceptions\NoContentException;
use App\Http\Controllers\DatabaseController;
use League\Flysystem\Config;
use Symfony\Component\HttpFoundation\Response;

class DatabaseMiddleware {

    public function handle($request, \Closure $next) {

        if ($request->method() != 'OPTIONS') {  //para pular qdo for metodo OPTIONS, pois não vem o cabeçalho correto
            $emp = '';
            $banco = new DatabaseController();

            if((!(starts_with(request()->path(), 'api')))&&(!(starts_with(request()->path(),'oauth')))){
                return $next($request);
            }
            if ($request->header('codempresa') != NULL){ //outras rotas
                $emp = $banco->getDatabase($request->header('codempresa'));
            }

            if (!empty($emp) && is_object($emp) && $emp->emp_status == 0) {
                throw new NoContentException('Sistema temporariamente bloqueado, favor entrar em contato com a Cartago para maiores informações.', Response::HTTP_CONFLICT);
            }
        }
        //Config::set('database.company', $emp->emp_banco);
        \Config::set('database.default', 'company');
        \Config::set("database.connections.company.database", $emp->emp_banco);
       // \config(['database.connections.company.database'=> $emp->emp_banco]);
       // dd( \config('database.connections.company'));
        return $next($request);
    }
}
