@extends('layout')

@section('title')
    Добавить дом
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Добавление дома</h3>
            <br>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if (count($errors) > 0)
                <alert id="alert-bag"></alert>
            @else
                <alert id="alert-bag" class="hidden"></alert>
            @endif
            <div class="hidden" id="errors">
                @if (count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {!! Form::open(['url' => 'buildings', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Имя:</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Номер дома" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Адресс:</label>
                <div class="col-sm-10">
                    <input type="text" name="address" class="form-control" id="name" placeholder="Адресс" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Организация:</label>
                <div class="col-sm-10">
                    {!! Form::select('organization_id', $organizations, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-info">Добавить</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        var bus = new Vue();

        new Vue({
            el: '#app'
        });

        document.getElementsByClassName('alert-message-bag')[0].innerHTML += document.getElementById('errors').innerHTML;
    </script>
@endsection