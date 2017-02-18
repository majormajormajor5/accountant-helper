@extends('layout')

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Дом: {{ $building->name }}</h3>
            <br>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-striped table-responsive table-bordered">
                <tr style="word-break: break-all">
                    <td>Номер</td>
                    <td>{{ $building->name }}</td>
                </tr>
                <tr style="word-break: break-all">
                    <td>Адресс</td>
                    <td>{{ $building->name }}</td>
                </tr>
                <tr style="word-break: break-all">
                    <td>Организация</td>
                    <td>
                        <a href="{{ url('organizations/' . $building->organization->id) }}">
                            {{ $building->organization->name }}
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection