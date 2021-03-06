<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>エキニシソフトボール</title>
    <link href={{asset("css/bootstraps.min.css")}} rel="stylesheet">
    <link href={{asset("css/bootstrap-theme.min.css")}} rel="stylesheet">
    <link href={{asset("css/bootstrap-datetimepicker.min.css")}} rel="stylesheet">
    <link href={{asset("css/mine.min.css")}} rel="stylesheet">
    <link href={{asset("css/bootstrap-switch.min.css")}} rel="stylesheet">
  </head>
  <body role="document">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <span class="navbar-brand">{!! Html::link('/logout', 'エキニシソフトボール') !!}</span>
        </div>
    </nav>

    <div class="container theme-showcase" role="main" style="max-width:1000px;">
      @if (Auth::check())
      <ul class="nav nav-tabs">
        @foreach($shareTabMenuItems as $item)
          @if ($item['is_admin'] == 0 || $is_admin == 1)
            @if ($shareActiveTabName === $item['name'])
              <li class="active"><a href="#">{!!$item['name']!!}</a></li>
            @else
              <li><a href="{{$item['url']}}">{!!$item['name']!!}</a></li>
            @endif
          @endif
        @endforeach
      </ul>
      @endif
      @yield('content')
    </div> <!-- /container -->
    @yield('template')
    <!-- jQuery -->
    <script src={{asset("js/jquery-2.1.4.min.js")}}></script>
    <script src={{asset("js/bootstrap.min.js")}}></script>
    <script src={{asset("js/vue.min.js")}}></script>
    <script src={{asset("js/jquery.blockui.min.js")}}></script>
    <script src={{asset("js/bootstrap-switch.min.js")}}></script>
    <script src={{asset("js/mine.min.js")}}></script>
    @yield('js')
  </body>
  <div class="a-center" style="font-size:8px;">
      Copyright &copy;  2016 nana-system All Rights Reserved.
  </div>
</html>
