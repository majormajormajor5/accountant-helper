@extends('layout')

@section('title')
    Организации
@endsection
@section('content')
    <div class="container">
        <div id="app">
            <div class="row">
                <div class="col-sm-12">
                    @if (empty($organizations))
                        <div class="alert alert-warning alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <h4>У вас нет ни одной организации </h4>
                            <a href="{{ url('organizations/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>
                        </div>
                    @else
                        <table class="table table-hover table-striped table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Имя организации</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $organization)
                                    <tr>
                                        <td>{{ $organization->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
