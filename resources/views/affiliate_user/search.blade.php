@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        $(document).ready(function(){
            //$('td').css('background-color','#000');

        });
    </script>
    <div class="container">


        @if(count($users) === 0)
            Не найдено
        @else
        <table class="table table-striped">

            <tr><td>Имя</td><td>email</td><td>phone</td><td></td></tr>

            @foreach ($users as $user)
                <tr><td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>

                </tr>
            @endforeach
        </table>
        @endif

    </div>
@endsection