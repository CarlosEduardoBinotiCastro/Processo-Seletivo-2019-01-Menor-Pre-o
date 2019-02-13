<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdutoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoutes()
    {
        $appURL = env('APP_URL');

        $urlsSuccess = [
            '/v1/produtos/7899921601051',
            '/v1/produtos/7899921601051/-20.849257/-41.114321',
        ];

        $urlsFall = [
            '/v1/produtos',
            '/v1/produtos/7899921601051//-41.114321',
        ];

        echo  PHP_EOL;
        echo "Testes de Sucesso";
        echo  PHP_EOL;
        echo  PHP_EOL;

        foreach ($urlsSuccess as $url) {
            $response = $this->get($url);
            if((int)$response->status() !== 200){
                echo  $appURL . $url . ' (FAILED), não retornou 200.';
                $this->assertTrue(false);
            } else {
                echo $appURL . $url . ' (Sucesso), retronou '.(int)$response->status();
                $this->assertTrue(true);
            }
            echo  PHP_EOL;
        }

        echo  PHP_EOL;
        echo "Testes de Falha";
        echo  PHP_EOL;
        echo  PHP_EOL;

        foreach ($urlsFall as $url) {
            $response = $this->get($url);
            if((int)$response->status() == 200){
                echo  $appURL . $url . ' (FAILED), retornou 200.';
                $this->assertTrue(false);
            } else {
                echo $appURL . $url . ' (Sucesso), retornou '.(int)$response->status();
                $this->assertTrue(true);
            }
            echo  PHP_EOL;
        }

    }
}
