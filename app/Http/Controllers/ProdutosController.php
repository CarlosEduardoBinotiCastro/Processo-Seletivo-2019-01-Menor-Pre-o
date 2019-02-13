<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{

    public function buscarProdutos($parametros){

        $parametros = explode('/',$parametros);

        if(count($parametros) >1){

            $gtin = $parametros[0];
            $lat = $parametros[1];
            $lon = $parametros[2];

            if( !(is_numeric($lat) &&  is_numeric($lon)) ){

                // LOG
                DB::table('log')->insert(['statusCode' => 302, 'gtin' => $parametros[0], 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => 0]);

                return redirect()->route('home')->with('erro', 'Valores de latitude e longitude devem ser numéricos!');
            }

        }else{

            $gtin = $parametros[0];
            $lat = null;
            $lon = null;

        }


        try {

            $produtos = DB::table('estabelecimentoProduto as estP')->orderBy('valorUnitario');
            $produtos->join('estabelecimento as est', 'est.estabelecimentoID', 'estP.estabelecimentoID');
            $produtos->where('gtin', '=', $gtin);
            $produtos->where('est.latitude', '!=', 0);
            $produtos->where('est.longitude', '!=', 0);
            $produtos = $produtos->get();

            if($produtos != null){
                foreach ($produtos as $produto) {
                    $produto->url = "http://maps.google.com/maps?q=".$produto->latitude.",".$produto->longitude;

                    if($lat != null && $lon != null){
                        $produto->distancia = $this->distancia($lat, $lon, $produto->latitude, $produto->longitude);
                    }
                }
            }

            // LOG
            DB::table('log')->insert(['statusCode' => 200, 'gtin' => $parametros[0], 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => count($produtos)]);

            return json_encode($produtos, JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {

            // LOG
            DB::table('log')->insert(['statusCode' => 302, 'gtin' => $parametros[0], 'dataRequisicao' => date('Y-m-d H:i:s'), 'numeroRegistros' => 0]);

            return redirect()->route('home')->with('erro', 'Ocorreu um Erro: '.$e->getMessage().' !');

        }

    }

    function distancia($lat1, $lon1, $lat2, $lon2) {

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $dist = (6371 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lon2 - $lon1 ) + sin( $lat1 ) * sin($lat2) ) );
        $dist = number_format($dist, 2, '.', '');
        return $dist." KM";
    }


}
