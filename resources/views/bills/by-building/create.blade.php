@extends('layout')

@section('title')
    Формирование счетов для дома {{ $building->name }}
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Формирование счетов для дома {{ $building->name }}
            </h3>
            <hr style="
                        border: 0;
                        height: 1px;
                        background: #333;
                        background-image: linear-gradient(to right, #ccc, #333, #ccc);
                      "
            >
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            {!! Form::open(['class' => 'form-horizontal', 'action' => ['BillsController@byBuildingStore', $building->id]]) !!}
                <div class="form-group">
                    <label class="control-label col-sm-2" for="month">Выберите месяц: </label>
                    <datepicker language="ru" id="month" name="month" format="MM yyyy"></datepicker>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="submit"></label>
                    <button type="submit" role="button" class="btn btn-info" id="submit">
                        Сформировать
                    </button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        var bus = new Vue({});

        var app = new Vue({
            el: '#app'
        });
    </script>
@endsection