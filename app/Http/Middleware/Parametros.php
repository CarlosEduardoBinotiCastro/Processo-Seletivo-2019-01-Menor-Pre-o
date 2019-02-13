<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class Parametros
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Middleware responsavel pela averiguação dos parametros

        // verifica se possui parametros

        if( count($request->route()->parameters()) > 0 ){
            $parametros = explode('/',$request->route()->parameters()['parametros']);

            // Verifica se passa mais de 3 parametros ou somente dois

            if(count($parametros) > 3  || count($parametros) == 2){

                // LOG
                DB::table('log')->insert(['statusCode' => 400, 'gtin' => $parametros[0], 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => 0]);

                return response('<div style="text-align:center;"> <br> <h1> BAD REQUEST 400 </h1> <br> <p> Sintaxe Esperada: <br><br> <b> v1/produtos/gtin/latitude/longitude </b> <br><br> ou <br><br> <b> v1/produtos/gtin/ </b> </p> <div>', 400);

            }else{

                // Verifica se passa parametros nulos na url

                if (in_array('', $parametros, true)) {

                    // LOG
                    DB::table('log')->insert(['statusCode' => 400, 'gtin' => $parametros[0], 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => 0]);

                    return response('<div style="text-align:center;"> <br> <h1> BAD REQUEST 400 </h1> <br> <p> Sintaxe Esperada: <br><br> <b> v1/produtos/gtin/latitude/longitude </b> <br><br> ou <br><br> <b> v1/produtos/gtin/ </b> </p> <div>', 400);

                }else{

                    // Verificação terminada

                    return $next($request);
                }
            }

        }else{

            // LOG
            DB::table('log')->insert(['statusCode' => 400, 'gtin' => "", 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => 0]);

            return response('<div style="text-align:center;"> <br> <h1> BAD REQUEST 400 </h1> <br> <p> Sintaxe Esperada: <br><br> <b> v1/produtos/gtin/latitude/longitude </b> <br><br> ou <br><br> <b> v1/produtos/gtin/ </b> </p> <div>', 400);

        }
    }
}
