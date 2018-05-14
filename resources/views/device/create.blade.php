@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
         <div for="serival_number" class="col-md-6">
             <a href="/device">Назад</a><br/><br/>
             {{Form::open(array('url' => 'device', 'method' => 'post'))}}
             <h2>Добавить устройство</h2><br/>
            <div class="form-group">
                <label >Введите имя устройства</label>
                {{Form::text('vehicle', '', ['class' => 'form-control', 'id' => 'vehcle', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите серийный номер устройства</label>
                {{Form::text('serial_number', '', ['class' => 'form-control', 'id' => 'serial_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите пароль устройства</label>
                {{Form::text('password', '', ['class' => 'form-control', 'id' => 'serival_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите персональный номер устройства</label>
                {{Form::text('personal_number', '', ['class' => 'form-control', 'id' => 'personal_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите тип устройства</label>
                {{Form::select('device_type',
                 ['vehicle' => 'Транспортное средство', 'stat' => 'Статическая камера'], null,
                 ['class' => 'form-control'])}}
            </div>
             <div class="form-group">
                 <label >Сигнализация</label>
                 {{Form::select('alarm_system',
                  ['0' => 'Выкл.', '1' => 'Вкл.'], '0',
                  ['class' => 'form-control'])}}
             </div>

            {{Form::submit('Добавить', array('class' => 'btn btn-primary')) }}
            {{Form::close()}}
        </div>
    </div>

@endsection