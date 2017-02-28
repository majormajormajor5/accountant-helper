@extends('layout')

@section('title')
    Список квартир дома {{ $building->name }} организации {{ $building->organization->name }}
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
                <table class="table table-hover table-striped table-responsive" id="mytable">
                    <thead>
                    <tr>
                        <th id="number">Номер</th>
                        <th id="owner">Владелец</th>
                        <th id="square">Площадь</th>
                        <th id="number_of_residents">Количество проживающих</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($apartments as $apartment)
                        <tr v-if="apartments[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="apartments[{{ $loop->index . '' }}] = false">
                            <td>
                                <input @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $apartment->number }}" id="{{ $apartment->id }}">
                            </td>
                            <td>
                                <a href="{{ url('owners/apartment/' . $apartment->id) }}">Владельцы</a>
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $apartment->square }}" id="{{ $apartment->id }}">
                            </td>
                            <td>
                                <input  @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $apartment->number_of_residents }}" id="{{ $apartment->id }}">
                            </td>
                            <td>
                                {!! Form::open(['url' => 'apartments/'. $apartment->id,
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
                                    <span class="glyphicon glyphicon-trash"></span>
                                    {{--@desktop Удалить@enddesktop--}}
                                </button>
                                {!! Form::close() !!}

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
        <h4 slot="body">Вы точно хотите удалить эту квартиру?</h4>
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
                    apartments: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $apartments }}")))
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
                        var apartmentId = el.id;
                        var cell = el.parentNode;

                        var x = cell.cellIndex;
                        var columnName = document.getElementById("mytable").rows[0].cells.item(x).id;

                        this.submitAjaxRequest(apartmentId, columnName, newValue, el, this.lastElementValue);
                    }
                },

                writeValue: function (e) {
                    var el = e.target;
                    this.lastElementValue = el.value;
                }, 
                
                submitAjaxRequest: function (apartmentId, columnName, value, el, oldValue) {
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

                    xmlhttp.open('POST', "{{ url('apartments') }}" + '/' + apartmentId + '/update', true);
                    xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("apartmentId=" + apartmentId + "&columnName=" + columnName + "&value=" + value);
                },

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
                }
            }
        });

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection