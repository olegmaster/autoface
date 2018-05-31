@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 style="color:#009688">Экстренные ситуации</h3><br/>
        <table class="table table-striped">
            <tr><td>Устройство</td><td>Время</td><td>Тип события</td><td>Статус обработка</td><td>Свидетели</td></tr>
            @foreach ($alarm_situations as $alarm_situation)
                <tr><td>{{$alarm_situation->getDevice()->first()->vehicle}}</td>
                    <td>{{$alarm_situation->time_fresh_signal}}</td>
                    <td>Прервано соединение</td>
                    <td>Обработано</td>
                    <td><a href="/situations-show/{{$alarm_situation->id}}">Посмотреть</a></td>
                </tr>
            @endforeach
        </table>
        {{ $alarm_situations->links() }}
    </div>
@endsection