@extends('layout')

@section('title')
    Счета дома {{ $building->name }}
    организации {{ $building->organization->name }}
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Счета дома {{ $building->name }}
                организации {{ $building->organization->name }}
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
            <button @click="showFilters = ! showFilters" class="btn btn-info">
                Фильтры
                <span v-show="! showFilters">+</span>
                <span v-show="showFilters">-</span>
            </button>
            <a role="button" type="button"  class="btn btn-info" href="{{ url('bills/building/' . $building->id . '/create') }}">
                Перейти к документам
            </a>
            {{--@if (empty($months->toArray()))--}}
                {{--<div class="alert alert-warning alert-dismissable">--}}
                    {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>--}}
                    {{--<h4>У данного дома пока нет квартир </h4>--}}
                    {{--<a href="{{ url('months/create') }}" class="btn btn-info"><span class="glyphicon glyphicon-plus"> </span> Добавить</a>--}}
                {{--</div>--}}
            {{--@else--}}
                    {!! Form::open(['method' => 'GET', 'class' => 'form-horizontal', 'v-show' => 'showFilters', 'style' => 'margin-top: 1em;']) !!}
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">С даты:</label>
                            <datepicker language="ru" name="from-date" format="dd-MM-yyyy" class="" value="{{ $request['from-date'] }}"></datepicker>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">По дату:</label>
                            <datepicker language="ru" name="to-date" format="dd-MM-yyyy" class="" value="{{ $request['to-date'] }}"></datepicker>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">C квартиры:</label>
                            <input type="text" name="from-apartment" value="{{ $request['from-apartment'] }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">По квартиру:</label>
                            <input type="text" name="to-apartment" value="{{ $request['to-apartment'] }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name"></label>
                            <button type="submit" class="btn btn-info">Отфильтровать</button>
                        </div>
                    {!! Form::close() !!}

                @if (! empty($months->toArray()))
                    <table class="table table-hover table-striped table-responsive" id="mytable">
                        <thead>
                        <tr>
                            <th id="apartment_number">Квартира</th>
                            <th id="month">Месяц</th>
                            <th id="taxes">Налоги</th>
                            <th id="beginning_sum">Сумма на начало</th>
                            <th id="ending_sum">Сумма на конец</th>
                            <th id="balance">Баланс</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($combMonths as $month)
                            <tr v-if="months[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="months[{{ $loop->index . '' }}] = false">
                                <td>
                                    <a href="#">{{ $month->apartment->number }}</a>
                                </td>
                                <td>
                                    <input type="text" value="{{ $month->month }}" id="month-{{ $month->id }}" readonly="readonly">
                                </td>
                                <td>
                                    <input type="text" value="" id="taxes-{{ $month->id }}" readonly="readonly">
                                    <a href="{{ url('taxes/month/' . $month->id . '/edit') }}"><span class="glyphicon glyphicon-wrench"></span></a>
                                </td>
                                <td>
                                    <input type="text" value="{{ $month->beginning_sum }}" id="beginning-sum-{{ $month->id }}" readonly="readonly">
                                </td>
                                <td>
                                    <input @focusout="checkChanges" @focusin="writeValue" type="text" value="{{ $month->ending_sum }}" id="ending-sum-{{ $month->id }}">
                                </td>
                                <td>
                                    <input type="text" value="{{ $month->balance }}" id="balance-{{ $month->id }}" readonly="readonly">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
        </div>
        {{--@endif--}}
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
                    showFilters: false,
                    message: 'message',
                    months: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $months }}"))),
                    additionalRows: []
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
                        var monthId = el.id.split('-').pop();
                        var cell = el.parentNode;

                        var x = cell.cellIndex;
                        var columnName = document.getElementById("mytable").rows[0].cells.item(x).id;

                        this.submitAjaxRequest(monthId, columnName, newValue, el, this.lastElementValue);
                    }
                },

                writeValue: function (e) {
                    var el = e.target;
                    this.lastElementValue = el.value;
                },

                submitAjaxRequest: function (monthId, columnName, value, el, oldValue) {
                    var xmlhttp = new XMLHttpRequest();

                    xmlhttp.onreadystatechange = function() {
                        //Clear previous alert message
                        if (document.getElementsByClassName('alert-message-bag')[0].innerHTML != '') {
                            app.emitClose();
                        }

                        if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
                            if (xmlhttp.status == 200) {
                                var response = JSON.parse(xmlhttp.responseText);
                                if (! response.success) {
                                    app.emitAlert();
                                    window.lastAJAXContainedErrors = true;
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

                    xmlhttp.open('POST', "{{ url('months') }}" + '/' + monthId, true);
                    xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//                    console.log(columnName, value, 'monthId:', monthId);
                    xmlhttp.send("_method=" + "PATCH" + "&_token=" + "{{ csrf_token() }}" + "&columnName=" + columnName + "&value=" + value);
                },

                deletemonth: function (e) {
                    e.preventDefault();
                    var el = e.target;
                    var button = el.querySelector('button[type="submit"]');
                    button.disabled = false;
                    var formData = new FormData(el);

                    var method = el.method.toUpperCase();
                    var url = el.action;
                    if (el.id) {
                        url = url + '/' + el.id.split('-').pop();
                    }

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

                submitAjaxRequestCreate: function () {
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
                                    document.getElementsByClassName('alert-message-bag')[0].innerHTML = '';
                                    for (var key in response.errors) {
                                        document.getElementsByClassName('alert-message-bag')[0].innerHTML += '<p>' + response.errors[key] + '</p>';
                                    }
                                } else {
                                    app.addmonth(response.monthId);
                                }
                            }
                        }
                    };

                    xmlhttp.open('POST', "{{ url('months') }}", true);
                    xmlhttp.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("_method=" + "POST" + "&buildingId=" + "{{ $buildingId }}");

                },

                addmonth: function (monthId) {
                    this.additionalRows.push({'monthId': monthId});
                }
            }
        });

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection
