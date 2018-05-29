@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        $(document).ready(function(){
            //$('td').css('background-color','#000');

        });
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">

                    {{ Form::open(array('url' => '/affiliate-user-search')) }}
                    <span class="input-group-btn">
                    {{ Form::submit('Поиск', array('class' => 'btn btn-default')) }}
                     </span>
                    {{ Form::text('name', '',  array('class' => 'form-control', 'placeholder' => "Искать пользователей для аффилиации ...")) }}
                    {{ Form::close() }}

                </div>
                <br/>

            </div>
            <div class="col-md-7">

            </div>
        </div>

        <table class="table table-striped">
            <tr><td>Имя</td><td>email</td><td>phone</td><td>Удалить из аффилированных</td><td></td></tr>

            @foreach ($affiliateUsers as $affiliateUser)
                <tr><td>{{$affiliateUser->getUser->name}}</td>
                    <td>{{$affiliateUser->getUser->email}}</td>
                    <td>{{$affiliateUser->getUser->phone}}</td>
                    <td><a href="/delete-affiliation/{{$affiliateUser->id}}" >Удалить</a></td>
                        </tr>
            @endforeach
        </table>

    </div>
@endsection