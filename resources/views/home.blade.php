@extends('layouts.app')
@section('content')


<div id="conteudo">

        <div id="Sucesso" class="container">
                <div class="col-md-8 offset-md-2">
                    @if(session()->has('sucesso'))
                        <br>
                        <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                            {{ session()->get('sucesso') }}
                        </div>
                    @endif
                </div>
        </div>


        <div id="Erro" class="container">
                <div class="col-md-8 offset-md-2">
                    @if(session()->has('erro'))
                        <br>
                        <div class="form-group row mb-0 alert alert-danger" style="font-size:20px">
                            {{ session()->get('erro') }}
                        </div>
                    @endif
                </div>
        </div>

        <br>

        <div class="container col-md-8">

                <div class="card">

                        <div class="card-header"> IMPORTAR CSV </div>

                        <div class="card-body">

                            <p> <b> Antes de realizar as consultas, é necessário importar a base de dados, caso contrário não retornará nenhum registro. </b> </p>

                            <a style="width: 150px;" class="btn btn-secondary" href='{{ url('/v1/importar') }}' id="btnImportar"> Importar Dados </a>

                            <p style="margin-top:2%"> <b> Arquivo CSV que será importado </b> </p>

                            <a style="width: 150px;" class="btn btn-secondary" href='{{ url('/v1/download') }}' id="btnImportar"> Download </a>


                        </div>

                </div>

        </div>

        <br>

        <div class="container col-md-8">

            <div class="card">

                    <div class="card-header"> APÓS IMPORTAR O ARQUIVO </div>

                    <div class="card-body">

                        <p> <b>  Rotas da API </b> </p>

                        <pre>/v1/produtos/gtin</pre>

                        <pre>/v1/produtos/gtin/latitude/longitude</pre>

                    </div>

            </div>

        </div>

</div>


<div class="container" id="carregando" style="display:none;">
        <br><br>
        <h2 class="offset-md-4"> Carregando Solicitação </h2>
        <br>
        <div class="loader offset-md-5"></div>
</div>





<script type="text/javascript">

    $(document).ready(function(){

        $("#btnImportar").click(function (){
            $("#carregando").css('display', 'block');
            $("#conteudo").css('display', 'none');
        });

    });

</script>


@endsection


