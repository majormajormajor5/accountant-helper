@extends('layout')

@section('title')
    Организации
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-10">
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
                            <th><a href="#" type="button" role="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> Добавить новую</a></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizations as $organization)
                            <tr>
                                <td style="word-break: break-all!important;">
                                    {{ $organization->name }}
                                </td>
                                <td>
                                    <a href="#" type="button" role="button" class="btn btn-info edit-button" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover"><span class="glyphicon glyphicon-edit"></span> Редактировать &nbsp; &nbsp;</a>
                                </td>
                                <td>
                                    <a href="#" type="button" role="button" class="btn btn-info" onclick="return confirm('Are you sure?');"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
                                </td>
                                <td>
                                    <a href="#" type="button" role="button" class="btn btn-info" onclick="return confirm('Are you sure?');"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        <div class="col-sm-2"></div>
        </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('.edit-button').popover();
        });
    </script>
@endsection