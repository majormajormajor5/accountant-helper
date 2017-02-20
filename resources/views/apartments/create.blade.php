@extends('layout')

@section('title')
    Добавить квартиры
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Добавление квартир</h3>
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

            {!! Form::open(['url' => 'apartments/building/' . $buildingId, 'method' => 'POST', 'class' => 'form-horizontal']) !!}


            <input type="hidden" name="buildingId" value="{{ $buildingId }}">
            {{--<div class="form-group">--}}
                {{--<label class="control-label col-sm-2" for="name">Организация:</label>--}}
                {{--<div class="col-sm-10">--}}
                    {{--{!! Form::select('building_id', $buildings, $buildingId, ['class' => 'form-control']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Количество</label>
                <div class="col-sm-10">
                    <input v-model="apartments.quantity" type="number" name="quantity" class="form-control" id="quantity" placeholder="Сколько вы хотите добавить" required>
                </div>
            </div>
            <div class="form-group" v-show="! isMoreThanOne()">
                <label class="control-label col-sm-2" for="name">Номер:</label>
                <div class="col-sm-10">
                        <input type="text" name="number" class="form-control" id="number" placeholder="Какой номер присвоить квартире">
                </div>
            </div>
            <div class="form-group" v-show="isMoreThanOne()">
                <label class="control-label col-sm-2" for="name">C номера:</label>
                <div class="col-sm-10">
                        <input type="text" name="fromNumber" class="form-control" id="fromNumber" placeholder="С какого номера включительно">
                </div>
            </div>
            <div class="form-group" v-show="isMoreThanOne()">
                <label class="control-label col-sm-2" for="name">По номер:</label>
                <div class="col-sm-10">
                        <input type="text" name="toNumber" class="form-control" id="toNumber" placeholder="По какой номер включительно">
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
            el: '#app',

            data: function () {
                return {
                    apartments: {
                        quantity: ''
                    }
                }
            },

            methods: {
                isMoreThanOne: function () {
                    if (parseInt(this.apartments.quantity) > 1) {
                        return true;
                    }

                    return false;
                }
            }
        });

        document.getElementsByClassName('alert-message-bag')[0].innerHTML += document.getElementById('errors').innerHTML;
    </script>
@endsection