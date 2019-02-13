<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Processo Seletivo </title>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" ></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>

        .loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid #3498db;
          width: 120px;
          height: 120px;
          -webkit-animation: spin 2s linear infinite; /* Safari */
          animation: spin 2s linear infinite;
        }

        /* Safari */
        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        .footer {
           position: fixed;
           left: 0;
           bottom: 0;
           width: 100%;
        }

    </style>


</head>
<body>

    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light navbar-laravel justify-content-center" style="background: #1f1f1f; font-size: 14px; color:white;">
            <b>API PARA CONSULTA DE PRODUTOS - VERSÃO 1</b>
        </nav>

        <main class="py-4" id="corpo">
            @yield('content')
        </main>

    </div>

    <footer class="footer" style="background-color:#1f1f1f;">
            <span style="font-size: 14px; float:right; color:white; margin-right:2%;">Processo Seletivo 2019-01. Desenvolvido por Carlos Eduardo Binoti de Castro</span>
    </footer>

</body>

<script>


</script>

</html>

