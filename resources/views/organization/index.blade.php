@extends('layout')

@section('title')
    Организации
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Список организаций &nbsp;&nbsp; <a href="{{ url('organizations/create') }}" type="button" role="button" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-plus"></span>@desktop Добавить новую@enddesktop</a></h3>
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
            @if (empty($organizations->toArray()))
                <div class="alert alert-warning alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <h4>У вас нет ни одной организации </h4>
                    <a href="{{ url('organizations/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>
                </div>
            @else
                <table class="table table-hover table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Имя организации</th>
                            <th></th>
                            {{--<th><a href="{{ url('organizations/create') }}" type="button" role="button" class="btn btn-info pull-right"><span class="glyphicon glyphicon-plus"></span>@desktop Добавить новую@enddesktop</a></th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organizations as $organization)
                            <tr v-if="organizations[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="organizations[{{ $loop->index . '' }}] = false" id="{{ 'org' . $loop->index }}">
                                <td style="word-break: break-all!important;">
                                    <a href="{{ url('organizations/' . $organization->id) }}">
                                        {{ $organization->name }}
                                    </a>
                                </td>
                                <td>
                                    {{--<div class="row">--}}
                                        {{--<div class="col-sm-7"></div>--}}
                                        {{--<div class="col-sm-3">--}}
                                            {{--<a href="{{ url('organizations/' . $organization->id . '/edit') }}"--}}
                                               {{--type="button"--}}
                                               {{--role="button"--}}
                                               {{--class="btn btn-info edit-button"--}}
                                            {{-->--}}
                                                {{--<span class="glyphicon glyphicon-edit"></span> @desktop Редактировать &nbsp; &nbsp; @enddesktop--}}
                                            {{--</a>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-2">--}}

                                            {{--{!! Form::open(['url' => 'organizations/'. $organization->id,--}}
                                                  {{--'method'=> 'DELETE',--}}
                                                  {{--'@submit' => 'deleteOrganization'--}}
                                                  {{--])--}}
                                            {{--!!}--}}
                                            {{--<button type="submit"--}}
                                                    {{--role="button"--}}
                                                    {{--class="btn btn-info"--}}
                                            {{-->--}}
                                                {{--<span class="glyphicon glyphicon-trash"></span>@desktop Удалить@enddesktop--}}
                                            {{--</button>--}}
                                            {{--{!! Form::close() !!}--}}

                                        {{--</div>--}}
                                    {{--</div>--}}


                                    {!! Form::open(['url' => 'organizations/'. $organization->id,
                                          'method'=> 'DELETE',
                                          '@submit' => 'deleteOrganization',
                                          'style'=> 'display: inline;',
                                          'class' => 'pull-right'
                                          ])
                                    !!}
                                    <button type="submit"
                                            role="button"
                                            class="btn btn-info"
                                    >
                                        <span class="glyphicon glyphicon-trash"></span>@desktop Удалить@enddesktop
                                    </button>
                                    {!! Form::close() !!}

                                    <button  class="btn btn-info pull-right" style="margin-right: 0.2em" onclick="window.location = '{{ url('organizations/' . $organization->id . '/edit') }}'">
                                        <span class="glyphicon glyphicon-edit"></span> @desktop Редактировать &nbsp; &nbsp; @enddesktop
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
            <h4 slot="body">Вы точно хотите удалить эту организацию?</h4>
            </vue-modal>
        </div>
@endsection

@section('js')
    <script>
//        Vue.directive('ajaxform', {
//            bind: function (el, binging, vnode) {
//                el.addEventListener('submit', function (e) {
//                    e.preventDefault();
//
//                    var button = el.querySelector('button[type="submit"]');
//                    button.disabled = false;
//                    var formData = new FormData(el);
//
//                    var method = el.method.toUpperCase();
//                    var url = el.action;
//                    window.lastReadyForSubmitForm = {};
//                    window.lastReadyForSubmitForm.formData = formData;
//                    window.lastReadyForSubmitForm.url = url;
//                    window.lastReadyForSubmitForm.method = method;
//                    window.lastReadyForSubmitForm.el = el;
//                    //Trigger click to make modal open
//                    document.getElementById('vue-show-modal').click();
//                });
//            }
//        });

        var bus = new Vue();

        var app = new Vue({
           el: '#app',

           created: function () {
               bus.$on('modal-confirmed', this.submitAjaxRequest);
           },

           methods: {
               deleteOrganization: function (e) {
                   e.preventDefault();
                   var el = e.target;
                   var button = el.querySelector('button[type="submit"]');
                   button.disabled = false;
                   var formData = new FormData(el);

                   var method = el.method.toUpperCase();
                   var url = el.action;
                   window.lastReadyForSubmitForm = {};
                   window.lastReadyForSubmitForm.formData = formData;
                   window.lastReadyForSubmitForm.url = url;
                   window.lastReadyForSubmitForm.method = method;
                   window.lastReadyForSubmitForm.el = el;
                   //Trigger click to make modal open
                   document.getElementById('vue-show-modal').click();
               },

               emitAlert: function () {
                   bus.$emit('new-message', this.message);
               },

               submitAjaxRequest: function () {
                   var xmlhttp = new XMLHttpRequest();

                   xmlhttp.onreadystatechange = function() {
                       if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                           if (xmlhttp.status == 200) {
                               document.getElementsByClassName('alert-message-bag')[0].innerHTML = xmlhttp.responseText;
                               if (xmlhttp.responseText !== '') {
                                   app.emitAlert();
                               }
                               else {
                                   simulateMouseEvent('dblclick', window.lastReadyForSubmitForm.el.closest('tr'), { ctrlKey: true })
                               }
                           }
                       }
                   };

                   xmlhttp.open(window.lastReadyForSubmitForm.method, window.lastReadyForSubmitForm.url, true);
                   xmlhttp.send(window.lastReadyForSubmitForm.formData);
               },

               deleteRow: function () {
                   this.organizations[0] = false;
               }
           },

           data: function () {
               return {
                   message: 'message',
                   showModal: false,
                   organizations: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $organizations }}")))
               }
           }
        });

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection