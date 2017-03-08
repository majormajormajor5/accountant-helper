@extends('layout')

@section('css')
    <link rel="stylesheet" href="/css/pignose.formula.css">
    <style>
        .constant-container {
            border-radius: 25px;
            border: 2px solid #ad0f06;
            padding: 20px;
            min-height: 5em;
            word-break: break-all;
        }

        .variable-container {
            border-radius: 25px;
            border: 2px solid #ad0f06;
            padding: 20px;
            min-height: 5em;
            word-break: break-all;
        }

        .formula-container {
            border: 0.2em solid #006b96;
            min-height: 150px;
        }
        .formula-custom {
            margin: 1em;
        }
    </style>
@endsection

@section('title')
    Редактировать налоги месяца
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Редактирование налогов месяца
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
            {{--<button class="btn btn-info" @click="showConstant()">Добавить константу</button>--}}
            <button class="btn btn-info" @click="showVariable()">Добавить переменную</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <form class="form form-horizontal">
                <div class="form-group" v-if="constant">
                    <label class="control-label col-sm-2" for="constant-name">Имя константы:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="constant-name" placeholder="Имя константы" v-model="constantName">
                    </div>
                </div>
                <div class="form-group" v-if="constant">
                    <label class="control-label col-sm-2" for="constant-value">Значение: </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="constant-value" placeholder="Значение" v-model="constantValue">
                    </div>
                </div>
                <div class="form-group" v-if="constant">
                    <label class="control-label col-sm-2" for="constant-save"></label>
                    <div class="col-sm-4">
                        <button id="constant-save" class="btn btn-info btn-block" @click.prevent="saveConstant()">Сохранить</button>
                    </div>
                </div>
                <div class="form-group" v-if="variable">
                    <label class="control-label col-sm-2" for="varibale-name">Имя переменной: </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="varibale-name" placeholder="Имя переменной" v-model="variableName">
                    </div>
                </div>
                <div class="form-group" v-if="variable">
                    <label class="control-label col-sm-2" for="variable-value">Значение: </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="variable-value" placeholder="Значение" v-model="variableValue">
                    </div>
                </div>
                <div class="form-group" v-if="variable">
                    <label class="control-label col-sm-2" for="variable-save"></label>
                    <div class="col-sm-4">
                        <button id="variable-save" class="btn btn-info btn-block" @click.prevent="saveVariable()">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--<div id="formula" class="formula"></div>--}}


    <div class="formula formula-advanced formula-container" id="formula-advanced"></div>

    <div class="formula-drop">
        <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
        {{--<h4>You can drag and drop each of below items to formula area.</h4>--}}
        {{--<p>If you want to set a value on your custom drag and drop item, Set <span class="label label-default">data-value</span> attribute on your element.</p>--}}

        {{--<h4>Константы:</h4>--}}
        {{--<div class="formula-drop-items constant-container" id="formula-drop-constants">--}}

        {{--</div>--}}

        <div class="formula-drop">
            <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
            <h4>Переменные:</h4>
            <div class="formula-drop-items variable-container" id="formula-drop-variables">

                <a href="#" class="formula-custom" data-value="month_number_of_residents">Количество проживающих</a>
                <a href="#" class="formula-custom" data-value="month_square">Площать</a>
            </div>
        </div>
    </div>
    <br>

    <span class="hidden" id="formula-string"></span>
    <div class="row">
        <div class="col-sm-12">
            {!! Form::open(['action' => ['TaxesController@update', $month->id], 'method' => 'PATCH', '@submit' => 'saveTaxes']) !!}
                <input type="hidden" id="taxes" name="taxes">
                <input type="hidden" value="{{ $month->building_id }}" name="building-id">
                <button type="button" role="button" @click="showFilters = ! showFilters" class="btn btn-info">
                    Применить формулу для
                    <span v-show="! showFilters">+</span>
                    <span v-show="showFilters">-</span>
                </button>
                <div v-show="showFilters">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">С даты:</label>
                        <datepicker language="ru" name="from-date" format="dd-MM-yyyy" class="" value=""></datepicker>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">По дату:</label>
                        <datepicker language="ru" name="to-date" format="dd-MM-yyyy" class="" value=""></datepicker>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">C квартиры:</label>
                        <input type="text" name="from-apartment" value="">
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="name">По квартиру:</label>
                        <input type="text" name="to-apartment" value="">
                    </div>
                </div>
                <button class="btn btn-info" role="button" id="form-submit">Сохранить формулу</button>
            {!! Form::close() !!}
            {{--<form @submit="saveTaxes">--}}
                {{--<button>submit</button>--}}
            {{--</form>--}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        jQuery = $;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="/js/formula.parser.js"></script>
    <script src="/js/pignose.formula.js"></script>
    <script>
        function calculateFormula() {
            $(function() {
                var $formula = $('#formula-advanced').formula();

                // You want to get loadable data like below, check code line 103.
//            var data = {"operator":"+","operand1":{"operator":"+","operand1":{"value":"1","type":"unit"},"operand2":{"value":"2","type":"unit"}},"operand2":{"value":"3","type":"unit"}};
//            $formula.data('formula').setFormula(data);

                $('.formula-drop .formula-drop-items .formula-custom').draggable({
                    revert: 'invalid',
                    helper: 'clone',
                    cancel: '',
                    scroll: false
                });

                $('.formula-advanced').droppable({
                    hoverClass: "formula-active",
                    drop: function( event, ui ) {
                        var $e = ui.draggable.clone();
                        $(this).formula('insert', $e);
                    }
                });
                document.querySelector('#formula-string').innerHTML = JSON.stringify(Object.assign({}, $formula.data('formula').getFormula().data));
            });
        }

        calculateFormula();

        var app = new Vue({
           el: '#app',

            data: function () {
                return {
                    {{--taxes: JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $taxes }}")),--}}
                    taxes: {},
                    showFilters: false,

                    constant: false,
                    variable: false,

                    constantName: '',
                    constantValue: '',
                    variableName: '',
                    variableValue: '',

                    constants: {},
                    variables: {}
                }
            },

            methods: {
               showConstant: function () {
                   if (this.constant == true && this.variable == false) {
                       return this.constant = false;
                   }
                   this.constant = true;
                   this.variable = false;
               },

               showVariable: function () {
                   if (this.constant == false && this.variable == true) {
                       return this.variable = false;
                   }
                   this.constant = false;
                   this.variable = true;
               },

               saveConstant: function () {
                   var element = document.createElement('a');
                   element.setAttribute('href', '#');
                   element.setAttribute('class', 'formula-custom');
                   element.setAttribute('data-value', this.constantName);
                   element.innerHTML = this.constantName;
                   //Add only if not exists
                   if (! this.constants[this.constantName]) {
                       this.constants[this.constantName] = this.constantValue;
                       document.querySelector('#formula-drop-constants').appendChild(element);
                   }
                   calculateFormula();
                   this.closeAll();
               },

               saveVariable: function () {
                   var element = document.createElement('a');
                   element.setAttribute('href', '#');
                   element.setAttribute('class', 'formula-custom');
                   element.setAttribute('data-value', this.variableName);
                   element.innerHTML = this.variableName;
                   if (! this.variableValue) {
                       this.variableValue = '1';
                   }
                   //Add only if not exists
                   if (! this.variables[this.variableName]) {
                       this.variables[this.variableName] = this.variableValue;
                       document.querySelector('#formula-drop-variables').appendChild(element);
                   }
                   calculateFormula();
                   this.closeAll();
               },

               closeAll: function () {
                   this.constant = false;
                   this.variable = false;
               },
                
               saveTaxes: function (e) {
                   e.preventDefault();
                   taxes = {};
                   taxes['constants'] = this.constants;
                   taxes['variables'] = this.variables;

                   var $formula = $('#formula-advanced').formula();

                   $('.formula-drop .formula-drop-items .formula-custom').draggable({
                       revert: 'invalid',
                       helper: 'clone',
                       cancel: '',
                       scroll: false
                   });

                   $('.formula-advanced').droppable({
                       hoverClass: "formula-active",
                       drop: function( event, ui ) {
                           var $e = ui.draggable.clone();
                           $(this).formula('insert', $e);
                       }
                   });
                   document.querySelector('#formula-string').innerHTML = JSON.stringify(Object.assign({}, $formula.data('formula').getFormula().data));
                   taxes['formula'] = JSON.parse(document.querySelector('#formula-string').innerHTML);
                   document.querySelector('#taxes').value = JSON.stringify(taxes);
                   e.target.submit();
               }
            }
        });
    </script>
@endsection