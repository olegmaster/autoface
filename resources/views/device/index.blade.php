@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        $(document).ready(function(){
            //$('td').css('background-color','#000');

        });
    </script>
    <div class="container">
        <button class="btn btn-success" onclick="window.location.href='/device/create'" id="btn-create-dev"  >Добавить устройство</button><br/><br/>
        <table class="table table-striped">
            <tr><td>Автомобиль</td><td>ID Устройства</td><td>Местоположение</td><td>Сигнализация</td><td>Редактировать</td><td></td></tr>

            @foreach ($devices as $device)
                <tr><td>{{ $device->vehicle }}</td><td>{{ $device->personal_number }}</td>
                    <td><pre>{{$device->location()}}</pre></td>
                    <td>
                        @if($device->alarm_system == 1)
                            Вкл.
                        @else
                            Выкл.
                        @endif
                    </td>
                    <td>
                        <a href="{{URL::to('/device/' . $device->id .'/edit')}}"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
                    <td>
                            {{ Form::open(array('url' => 'device/' . $device->id, 'class' => 'pull-right')) }}
                            {{ Form::hidden('_method', 'DELETE') }}

                            {{ Form::submit('Удалить', array('class' => 'btn btn-danger')) }}
                            {{ Form::close() }}

                        </td></tr>
            @endforeach
        </table>
            {{ $devices->links() }}
    </div>
@endsection