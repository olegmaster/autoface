@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 style="color:#009688">Экстренные ситуации</h3><br/>
        <table class="table table-striped">
            <tr><td>Устройство</td><td>Время</td><td>Тип события</td><td>Статус обработка</td></tr>
            @foreach ($messages as $message)
                <tr><td>1</td><td></td><td></td><td></td></tr>
            @endforeach
        </table>
        {{ $messages->links() }}
    </div>
@endsection