@extends('layout')

@section('title')
    Владельцы квартиры {{ $apartment->number }} дома {{ $apartment->building->name }} организации {{ $apartment->building->organization->name }}
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Владельцы квартиры номер {{ $apartment->number }} дома {{ $apartment->building->name }} организации {{ $apartment->building->organization->name }}
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
    <button @click="addOwner">add+</button>
    <div class="row">
        <div class="col-sm-12">
            <alert-hidden id="my-alert"></alert-hidden>
            <section></section>
            @if (empty($owners->toArray()))
                <div class="alert alert-warning alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <h4>У данного дома пока нет квартир </h4>
                    <a href="{{ url('owners/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>
                </div>
            @else
                <table class="table table-hover table-striped table-responsive" id="mytable">
                    <thead>
                    <tr>
                        <th id="second_name">Фамилия</th>
                        <th id="first_name">Имя</th>
                        <th id="patronymic">Отчество</th>
                        <th id="email">email</th>
                        <th id="phone">Телефон</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($owners as $owner)
                        <tr v-if="owners[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="owners[{{ $loop->index . '' }}] = false">
                            <td>
                                <input @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $owner->second_name }}" id="{{ $owner->id }}">
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $owner->first_name }}" id="{{ $owner->id }}">
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $owner->patronymic }}" id="{{ $owner->id }}">
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $owner->email }}" id="{{ $owner->id }}">
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $owner->phone }}" id="{{ $owner->id }}">
                            </td>
                            <td>
                                {!! Form::open(['url' => 'owners/'. $owner->id,
                                      'method'=> 'DELETE',
                                      '@submit' => 'deleteOwner',
                                      'style'=> 'display: inline;',
                                      'class' => 'pull-right'
                                      ])
                                !!}
                                <button type="submit"
                                        role="button"
                                        class="btn btn-info"
                                >
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr v-for="(additionalRow, index) in additionalRows">
                        <td>
                            <input @focusout="checkChanges" @focusin="writeValue" type="text" value="" id="">
                        </td>
                        <td>
                            <input @focusout="checkChanges" @focusin="writeValue" type="text" value="" id="">
                        </td>
                        <td>
                            <input @focusout="checkChanges" @focusin="writeValue" type="text" value="" id="">
                        </td>
                        <td>
                            <input @focusout="checkChanges" @focusin="writeValue" type="text" value="" id="">
                        </td>
                        <td>
                            <input @focusout="checkChanges" @focusin="writeValue" type="text" value="" id="">
                        </td>
                        <td>
                            {!! Form::open(['url' => 'owners/'. 1,
                                  'method'=> 'DELETE',
                                  '@submit' => 'deleteOwner',
                                  'style'=> 'display: inline;',
                                  'class' => 'pull-right'
                                  ])
                            !!}
                            <button type="submit"
                                    role="button"
                                    class="btn btn-info"
                            >
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
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
        <h4 slot="body">Вы точно хотите удалить этого владельца ?</h4>
        </vue-modal>
    </div>
@endsection

@section('js')
    <script>
        var bus = new Vue;

        var app = new Vue({
            el: '#app',

            created: function () {
                bus.$on('modal-confirmed', this.submitAjaxRequestDelete);
            },

            data: function () {
                return {
                    showModal: false,
                    message: 'message',
                    owners: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $owners }}"))),
                    additionalRows: ['', '']
                }
            },

            methods: {

                emitAlert: function () {
                    bus.$emit('new-message', this.message);
                },

                emitClose: function () {
                    bus.$emit('close', this.message);
                },

                checkChanges: function (e) {
                    var el = e.target;
                    var newValue = e.target.value;

                    if (newValue !== this.lastElementValue) {
                        var ownerId = el.id;
                        var cell = el.parentNode;

                        var x = cell.cellIndex;
                        var columnName = document.getElementById("mytable").rows[0].cells.item(x).id;

                        this.submitAjaxRequest(ownerId, columnName, newValue, el, this.lastElementValue);
                    }
                },

                writeValue: function (e) {
                    var el = e.target;
                    this.lastElementValue = el.value;
                },

                submitAjaxRequest: function (ownerId, columnName, value, el, oldValue) {
                    var xmlhttp = new XMLHttpRequest();

                    xmlhttp.onreadystatechange = function() {
                        if (document.getElementsByClassName('alert-message-bag')[0].innerHTML != '') {
                            app.emitClose();
                        }

                        if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                            if (xmlhttp.status == 200) {
                                var response = JSON.parse(xmlhttp.responseText);
                                if (! response.success) {
                                    app.emitAlert();
                                    window.lastAJAXContainedErrors = true;
//                                    console.log(window.lastAJAXContainedErrors);
                                    document.getElementsByClassName('alert-message-bag')[0].innerHTML = '';
                                    for (var key in response.errors) {
                                        document.getElementsByClassName('alert-message-bag')[0].innerHTML += '<p>' + response.errors[key] + '</p>';
                                    }

                                    el.value = oldValue;
                                } else {
                                    window.lastAJAXContainedErrors = false;
                                }
                            }
                        }
                    };

                    xmlhttp.open('POST', "{{ url('owners') }}" + '/' + ownerId + '/update', true);
                    xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("ownerId=" + ownerId + "&columnName=" + columnName + "&value=" + value);
                },

                deleteOwner: function (e) {
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

                submitAjaxRequestDelete: function () {
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

                addOwner: function () {
                    this.additionalRows.unshift({text: 'sometext'});
                }
            }
        });

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection

