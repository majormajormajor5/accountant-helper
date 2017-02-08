@extends('layout')

@section('title')
    Добавить организацию
@endsection

@section('content')
    <div class="container">
        <div id="app">
            <div class="row">
                <div class="col-sm-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::model($organization, ['url' => 'organizations', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Имя:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Имя организации" required>
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
            <example></example>
        </div>
    </div>
    {{--<alert></alert>--}}

@endsection

@section('js')
    <script>
//        Vue.component('example', require('./components/Example.vue'));
//
//        new Vue({
//            'el': '#app'
//        });
    </script>
@endsection