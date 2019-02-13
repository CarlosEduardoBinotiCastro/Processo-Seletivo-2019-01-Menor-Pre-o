<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{

    public function importar(){

        if(DB::table('importardados')->count()){

            return redirect()->route('home')->with('erro', "A importação já foi concluída !");

        }else{
            $path = storage_path('../database/dataset-processo-seletivo-2019.csv');

            try {

                $data = collect(file($path));
                $partes = $data->chunk(1000);


                if (count($data) > 0) {

                    $header = explode(',',$partes[0][0]);

                    $header[19] = trim(preg_replace('/\s\s+/', ' ', $header[19]));

                    $partes[0]->shift();

                    DB::beginTransaction();

                    foreach ($partes as $parte) {

                        $parteReal = array_map('str_getcsv', $parte->toArray());

                        foreach ($parteReal as $row) {

                            $longitude = trim(preg_replace('/\s\s+/', ' ', $row[19]));

                            DB::table('importardados')->insert([ $header[0] => $row[0], $header[1] => $row[1], $header[2] => $row[2], $header[3] => $row[3], $header[4] => $row[4], $header[5] => $row[5], $header[6] => $row[6], $header[7] => $row[7], $header[8] => $row[8], $header[9] => $row[9], $header[10] => $row[10], $header[11] => $row[11], $header[12] => $row[12], $header[13] => $row[13], $header[14] => $row[14], $header[15] => $row[15], $header[16] => $row[16], $header[17] => $row[17], $header[18] => $row[18], $header[19] => $longitude ]);

                        }

                    }

                    DB::commit();

                    return redirect('/v1/padronizar');


                } else {

                    return redirect()->route('home')->with('erro', 'Nenhuma linha foi importada, Tente novamente');

                }

           } catch (\Exception $e) {

                DB::rollBack();

                return redirect()->route('home')->with('erro', 'Aconteceu um ero durante processo,  ERRO: '.$e->getMessage());

           }
        }

    }



    // PADRONIZAR DADOS


    public function padronizarDados(){

        //FORMATANDO TABELA MUNICIPIOS

        try {

            DB::beginTransaction();

            $municipios =  DB::table('importardados')->orderBy('NME_MUNICIPIO')->select('NME_MUNICIPIO', 'COD_MUNICIPIO_IBGE', 'NME_SIGLA_UF')->groupBy('COD_MUNICIPIO_IBGE')->get();

            foreach ($municipios as $municipio) {

                $id = intval($municipio->COD_MUNICIPIO_IBGE);

                DB::table('municipio')->insert(['municipioID' => $id, 'municipioNome' => $municipio->NME_MUNICIPIO, 'siglaUF' => $municipio->NME_SIGLA_UF]);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            DB::table('importardados')->delete();

            return redirect()->route('home')->with('erro', 'Aconteceu um ero durante a formatação da tabela MUNICIPIO  ERRO: '.$e->getMessage());

        }



        //FORMATANDO TABELA LOGRADOURO


        try {

            DB::beginTransaction();

            $logradouros =  DB::table('importardados')->orderBy('NME_LOGRADOURO')->select('COD_NUMERO_LOGRADOURO', 'NME_LOGRADOURO', 'NME_BAIRRO', 'COD_MUNICIPIO_IBGE', 'COD_CEP')->groupBy('COD_NUMERO_LOGRADOURO')->get();

            foreach ($logradouros as $logradouro) {

                $municipioID = intval($logradouro->COD_MUNICIPIO_IBGE);
                $id = intval($logradouro->COD_NUMERO_LOGRADOURO);

                DB::table('logradouro')->insert(['logradouroID' => $id, 'municipioID' => $municipioID, 'logradouroNome' => $logradouro->NME_LOGRADOURO, 'bairro' => $logradouro->NME_BAIRRO, 'cep' => 'COD_CEP']);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            DB::table('importardados')->delete();
            DB::table('municipio')->delete();

            return redirect()->home('home')->with('erro', 'Aconteceu um ero durante a formatação da tabela LOGRADOURO  ERRO: '.$e->getMessage());

        }


        //FORMATANDO TABELA ESTABELECIMENTO

        try {

            DB::beginTransaction();

            $estabelecimentos =  DB::table('importardados')->orderBy('NME_ESTABELECIMENTO')->select('ID_ESTABELECIMENTO', 'NME_ESTABELECIMENTO', 'NUM_LATITUDE', 'NUM_LONGITUDE', 'NME_COMPLEMENTO', 'COD_NUMERO_LOGRADOURO')->groupBy('ID_ESTABELECIMENTO')->get();

            foreach ($estabelecimentos as $estabelecimento) {

                $logradouroID = intval($estabelecimento->COD_NUMERO_LOGRADOURO);

                $id = intval($estabelecimento->ID_ESTABELECIMENTO);

                $latitude = floatval($estabelecimento->NUM_LATITUDE);

                $longitude = floatval($estabelecimento->NUM_LONGITUDE);

                DB::table('estabelecimento')->insert(['estabelecimentoID' => $id, 'logradouroID' => $logradouroID, 'estabelecimentoNome' => $estabelecimento->NME_ESTABELECIMENTO, 'latitude' => $latitude, 'longitude' => $longitude, 'complemento' => $estabelecimento->NME_COMPLEMENTO]);
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            DB::table('importardados')->delete();
            DB::table('logradouro')->delete();
            DB::table('municipio')->delete();


            return redirect()->route('home')->with('erro', 'Aconteceu um ero durante a formatação da tabela ESTABELECIMENTO  ERRO: '.$e->getMessage());

        }


        //FORMATANDO TABELA Produto Estabelecimento

        try {

            DB::beginTransaction();

            $produtosEstabelecimento =  DB::table('importardados')->orderBy('NM_ESTABELECIMENTO')->select('ID_ESTABELECIMENTO', 'COD_GTIN', 'DSC_PRODUTO', 'VLR_UNITARIO')->groupBy('COD_GTIN', 'ID_ESTABELECIMENTO')->get();


            foreach ($produtosEstabelecimento as $produtoEstabelecimento) {

                $gtin = $produtoEstabelecimento->COD_GTIN;


                $estabelecimentoID = intval($produtoEstabelecimento->ID_ESTABELECIMENTO);

                $valor = floatval($produtoEstabelecimento->VLR_UNITARIO);

                DB::table('estabelecimentoProduto')->insert(['estabelecimentoID' => $estabelecimentoID, 'gtin' => $gtin, 'valorUnitario' => $valor, 'descricao' => $produtoEstabelecimento->DSC_PRODUTO]);

            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            DB::table('importardados')->delete();
            DB::table('estabelecimento')->delete();
            DB::table('logradouro')->delete();
            DB::table('municipio')->delete();

            return redirect()->route('home')->with('erro', 'Aconteceu um ero durante a formatação da tabela ESTABELECIMENTO PRODUTO  ERRO: '.$e->getMessage());

        }

        return redirect()->route('home')->with('sucesso', 'Base de dados importada, pronta para uso !');

    }


    public function apagar(){

            DB::table('importardados')->delete();
            DB::table('estabelecimentoProduto')->delete();
            DB::table('estabelecimento')->delete();
            DB::table('logradouro')->delete();
            DB::table('municipio')->delete();

            return redirect()->route('home')->with('sucesso', 'Base de dados Apagada !');

    }


    public function download(){
        $path = storage_path('../database/dataset-processo-seletivo-2019.csv');
        return response()->download($path);
    }


}
