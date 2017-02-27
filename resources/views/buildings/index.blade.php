@extends('layout')

@section('title')
    Дома
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Список домов &nbsp;&nbsp;
                <a href="{{ url('buildings/create') }}" type="button" role="button" class="btn btn-info btn-sm">
                    <span class="glyphicon glyphicon-plus"></span>@desktop Добавить новый@enddesktop
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
            @if (empty($buildings->toArray()))
                <div class="alert alert-warning alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <h4>У вас нет созданных домов </h4>
                    <a href="{{ url('buildings/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>
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
                    @foreach ($buildings as $building)
                        <tr v-if="buildings[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="buildings[{{ $loop->index . '' }}] = false" id="{{ 'org' . $loop->index }}">
                            <td style="word-break: break-all!important;">
                                <a href="{{ url('buildings/' . $building->id) }}">
                                    {{ $building->name }}
                                </a>
                            </td>
                            <td style="word-break: break-all!important;">
                                {{ $building->address }}
                            </td>
                            <td style="word-break: break-all!important;">
                                <a href="{{ url('organizations/' . $building->organization->id) }}">
                                    {{ $building->organization->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('buildings/' . $building->id . '/apartments') }}">Квартиры</a>
                            </td>
                            <td>
                                {!! Form::open(['url' => 'buildings/'. $building->id,
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

                                <button  class="btn btn-info pull-right" style="margin-right: 0.2em" onclick="window.location = '{{ url('buildings/' . $building->id . '/edit') }}'">
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
        <h4 slot="body">Вы точно хотите удалить этот дом?</h4>
        </vue-modal>
    </div>
@endsection

@section('js')
    <script>
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
                    this.buildings[0] = false;
                }
            },

            data: function () {
                return {
                    message: 'message',
                    showModal: false,
                    buildings: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $buildings }}")))
                }
            }
        });

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection