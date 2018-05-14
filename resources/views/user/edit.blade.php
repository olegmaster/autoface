@extends('layouts.app')

@section('content')


    <div class="row justify-content-center">

        <div for="serival_number" class="col-md-6">
            <button class="btn btn-link" onclick="window.history.back()">Назад</button>

            <br/>

            <br/>
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <h2>Редактировать профиль</h2><br/>
            {{Form::open(array('url' => 'user/update' , 'method' => 'post'))}}
            <div class="form-group">
                <label >Введите имя устройства</label>
                {{Form::text('name', $user->name , ['class' => 'form-control', 'id' => 'vehcle', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите серийный номер устройства</label>
                {{Form::email('email', $user->email , ['class' => 'form-control', 'id' => 'serial_number', 'required'])}}
            </div>
            <div class="form-group">
                <label >Введите пароль устройства</label>
                {{Form::text('phone', $user->phone , ['class' => 'form-control', 'id' => 'serival_number', 'required'])}}
            </div>
            {{Form::submit('Обновить', array('class' => 'btn btn-primary')) }}
            {{Form::close()}}
        </div>
    </div>


    <div class="footer">
        <div class="camera-select">
            <h4>Камеры</h4>
            <div id="camera-1"></div>
            <div id="camera-2"></div>
            <div id="camera-3"></div>
            <div id="camera-4"></div>
        </div>
        <p></p>
    </div>
@endsection
