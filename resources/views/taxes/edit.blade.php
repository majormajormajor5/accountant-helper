@extends('layout')

@section('css')
    <link rel="stylesheet" href="/css/pignose.formula.css">
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
    {{--<div id="formula" class="formula"></div>--}}

    <div class="formula formula-advanced" id="formula-advanced"></div>

    <div class="formula-drop">
        <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
        <h4>You can drag and drop each of below items to formula area.</h4>
        <p>If you want to set a value on your custom drag and drop item, Set <span class="label label-default">data-value</span> attribute on your element.</p>
        <div class="formula-drop-items">
            <a href="#" class="formula-custom" data-value="3.14">PI</a>
            <a href="#" class="formula-custom formula-custom-operator" data-value="+">Custom operator (+)</a>
            <a href="#" class="formula-custom" data-value="3">Variable (INT 3)</a>
        </div>
    </div>
    {{ var_dump($month->taxes) }}
@endsection

@section('js')
    <script>
        jQuery = $;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="/js/formula.parser.js"></script>
    <script src="/js/pignose.formula.js"></script>
    <script>

//        $(function() {
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

//        });
        function getFormula() {
            console.log($formula.data('formula').getFormula().data);
        }
    </script>
@endsection