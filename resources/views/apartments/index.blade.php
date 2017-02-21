@extends('layout')

@section('title')
    Дома
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Список квартир дома {{ $building->name }} организации {{ $building->organization->name }}
                <a href="{{ url('apartments/building/'. $building->id . '/create') }}" type="button" role="button" class="btn btn-info btn-sm">
                    <span class="glyphicon glyphicon-plus"></span>
                    @desktop Добавить@enddesktop
                </a>
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
            <alert-hidden id="my-alert"></alert-hidden>
            <section></section>
            @if (empty($apartments->toArray()))
                <div class="alert alert-warning alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <h4>У данного дома пока нет квартир </h4>
                    <a href="{{ url('apartments/building/' . $building->id . '/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>
                </div>
            @else
                <table class="table table-hover table-striped table-responsive">
                    <thead>
                    <tr>
                        <th>Номер</th>
                        <th>Адресс</th>
                        <th>Организация</th>
                        <th>Квартиры</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
        </div>
        @endif
    </div>

    <button id="vue-show-modal" v-show="false" @click="showModal = true" class="hidden">Show Modal</button>
    <!-- use the modal component, pass in the prop -->
    <div class="hidden vue-hidden-container">
        <vue-modal v-show="showModal" @close="showModal = false">
        <!--
          you can use custom content here to overwrite
          default content
        -->
        <h4 slot="body">Вы точно хотите удалить этот дом?</h4>
        </vue-modal>
    </div>
@endsection

@section('js')
    <script>
        var bus = new Vue;

        var app = new Vue({
            el: '#app'
        });
    </script>
@endsection