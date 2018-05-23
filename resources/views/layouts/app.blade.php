<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/clock.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>





    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive-navigation-menu.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <script>
        trackAlarmWork();
        function trackAlarmWork(){
            setInterval(function(){
                serverCheckAlarm();
            }, 5000);
        }

        function serverCheckAlarm(){
            $.ajax({
                type:'POST',
                url:'/check/alarm',
                success:function(data){
                    if(data.status == 'alarm'){
                        var device_name = data.device_name;
                        showAlarmMessage(device_name);

                        addMessageAboutAlarm(data.device_id);
                    }
                }
            });
        }

        function addMessageAboutAlarm(device_id){
            var data = {
                device_id: device_id
            }
            var data = data;
            $.ajax({
                type:'POST',
                url:'/message/handle',
                data: data,
                success:function(data){
                    console.log(data);
                }
            });
        }

        function showAlarmMessage(device_name){
            $(document).ready(function(){
                var message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Сработала сигнализация</strong> устройство ' + device_name + '&nbsp</div>';
                $('.message-container').html(message);
            })

        }

    </script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span id="clock">&nbsp;</span>
                    <div class="message-container">

                    </div>

                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>



        @section('sidebar')
            <div class="nav-side-menu">
                <div class="brand">Autoface</div>
                <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

                <div class="menu-list">

                    <ul id="menu-content" class="menu-content collapse out">

                        <li data-toggle="collapse" data-target="#devices"  class="{{ Request::is('map') ? 'active' : '' }}">
                            <a href="/map"><i class="glyphicon glyphicon-th-large"></i>&nbsp; &nbsp;&nbsp;Карта<span style="opacity:0">КартаКартаКартаКартаКарта</span></a>

                        </li>
                        @if(Request::is('map'))
                            <h5>Мои устройства</h5>
                            <ul class="sub-menu" id="devices">

                            @foreach($devices as $device)
                                <li class="devices-list" data-id="{{$device->id}}">{{$device->vehicle}}<input class="put-to-alarm-box" type="checkbox" value="" @if ($device->alarm_system)
                                        checked
                                                @endif data-id="{{$device->id}}" > </li>
                            @endforeach
                            </ul>
                        @endif
                        <li class="{{ Request::is('device') ? 'active' : '' }}">
                            <a href="/device">
                                <i class="fa fa-user fa-lg"></i> Диспетчер устройств
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="fa fa-user fa-lg"></i> Видеоархив
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-user fa-lg"></i> Статистика
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo route('profile'); ?>">
                                <i class="fa fa-user fa-lg"></i> Настроить профиль
                            </a>
                        </li>
                        <li>
                            <a href="/situations">
                                <i class="fa fa-user fa-lg"></i> Экстренные ситуации
                            </a>
                        </li>
                        {{--https://bootsnipp.com/snippets/featured/responsive-navigation-menu--}}
                    </ul>
                </div>
            </div>
        @show

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
