@extends('layouts.app')

@section('content')



    <div class="row justify-content-center">
        <div for="serival_number" class="col-md-6">
            <button class="btn btn-link" onclick="window.history.back()">Назад</button>
            <br/><br/>
            <h2>Редактировать устройство</h2><br/>
            {{Form::open(array('url' => 'device/' . $device->id , 'method' => 'put'))}}
            <div class="form-group">
                <label >Введите имя устройства</label>
                {{Form::text('vehicle', $device->vehicle , ['class' => 'form-control', 'id' => 'vehcle', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите серийный номер устройства</label>
                {{Form::text('serial_number', $device->serial_number , ['class' => 'form-control', 'id' => 'serial_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите пароль устройства</label>
                {{Form::text('password', $device->password , ['class' => 'form-control', 'id' => 'serival_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите персональный номер устройства</label>
                {{Form::text('personal_number', $device->personal_number , ['class' => 'form-control', 'id' => 'personal_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите тип устройства</label>
                {{Form::select('device_type',
                 ['vehicle' => 'Транспортное средство', 'stat' => 'Статическая камера'], $device->device_type,
                 ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                <label >Сигнализация</label>
                {{Form::select('alarm_system',
                 ['0' => 'Выкл.', '1' => 'Вкл.'], $device->alarm_system,
                 ['class' => 'form-control'])}}
            </div>



            {{Form::submit('Обновить', array('class' => 'btn btn-primary')) }}
            {{Form::close()}}
        </div>
    </div>

@endsection