@extends('layout')

@section('title')
    Организации
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-10">
            <alert-hidden id="my-alert"></alert-hidden>
            <section ></section>
            @if (!$organizations)
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
                            <th><a href="{{ url('organizations/create') }}" type="button" role="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span>@desktop Добавить новую@enddesktop</a></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organizations as $organization)
                            <tr>
                                <td style="word-break: break-all!important;">
                                    {{ $organization->name }}
                                </td>
                                <td>
                                    <a href="#" type="button" role="button" class="btn btn-info edit-button" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover"><span class="glyphicon glyphicon-edit"></span> @desktop Редактировать &nbsp; &nbsp; @enddesktop</a>
                                </td>
                                <td>
                                    {!! Form::open(['url' => 'organizations/'. $organization->id, 'method'=> 'DELETE', '@submit.prevent' => 'deleteOrganization', 'v-ajaxform' => 'true']) !!}
                                        <button href="{{ url('organizations/' . $organization->id) }}"
                                                type="submit" role="button" class="btn btn-info"
                                                {{--data-toggle="modal"--}}
                                                {{--data-target="#myModal"--}}
                                        >
                                            <span class="glyphicon glyphicon-trash"></span>@desktop Удалить@enddesktop
                                        </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            <div class="col-sm-2"></div>
        </div>

        {{--<h2>Modal Example</h2>--}}
        {{--<!-- Trigger the modal with a button -->--}}
        {{--<button type="button" class="btn btn-info btn-lg" >Open Modal</button>--}}

        {{--<!-- Modal -->--}}
        {{--<div class="modal fade" id="myModal" role="dialog">--}}
            {{--<div class="modal-dialog">--}}

                {{--<!-- Modal content-->--}}
                {{--<div class="modal-content">--}}
                    {{--<div class="modal-header">--}}
                        {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                        {{--<h4 class="modal-title">Modal Header</h4>--}}
                    {{--</div>--}}
                    {{--<div class="modal-body">--}}
                        {{--<p>Some text in the modal.</p>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
@endsection

@section('js')
    <script>
        Vue.directive('ajaxform', {
            bind: function (el, binging, vnode) {
                el.addEventListener('submit', function (e) {
                    e.preventDefault();

                    var button = el.querySelector('button[type="submit"]');
                    button.disabled = false;
                    var resetForm = true;
                    var formData = new FormData(el);

                    var formInstance = el;
                    var method = el.method.toUpperCase();
                    var url = el.action;

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                            if (xmlhttp.status == 200) {
                                document.getElementsByClassName('alert-message-bag')[0].innerHTML = xmlhttp.responseText;
                                if (xmlhttp.responseText !== '') {
                                    app.emitAlert();
                                }
                            }
                        }
                    };

                    xmlhttp.open(method, url, true);
                    xmlhttp.send(formData);
                });
            }
        });

        var bus = new Vue();

        var app = new Vue({
           el: '#app',

           methods: {
               deleteOrganization: function () {

               },

               emitAlert: function () {
                   bus.$emit('new-message', this.message);
               }
           },

           data: function () {
               return {
                   message: 'message'
               }
           }
        });
    </script>
@endsection