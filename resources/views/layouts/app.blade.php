<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Multilayer TaskList</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--AjaxのCSRF対策-->
        <!--JS側の設定も必要-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ secure_asset('/css/main.css') }}">
    </head>

    <body>
        @include('commons.navbar')
        
        <div class="container">
            @include('commons.error_messages')
            
            @yield('content')
        </div>
        
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
        
        <!--d3.js-->
        <script src="https://d3js.org/d3.v5.min.js"></script>
        
        <script type="text/javascript" src="{{ secure_asset('/js/tasksTodata.js') }}"></script>
        <script type="text/javascript" src="{{ secure_asset('/js/treeview.js') }}"></script>
        <script type="text/javascript" src="{{ secure_asset('/js/checkbox.js') }}"></script>
        <!--<script type="text/javascript" src="js/integrated.js"></script>-->
    </body>
</html>