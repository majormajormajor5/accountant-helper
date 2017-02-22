@extends('layout')

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Организация: {{ $organization->name }}</h3>
            <br>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-striped table-responsive table-bordered">
                <tr style="word-break: break-all">
                    <td>Имя</td>
                    <td>{{ $organization->name }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection