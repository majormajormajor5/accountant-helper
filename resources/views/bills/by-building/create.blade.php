@extends('layout')

@section('title')
    Формирование счетов для дома {{ $building->name }}
@endsection

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>
                Формирование счетов для дома {{ $building->name }}
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
            {!! Form::open(['class' => 'form-horizontal', 'action' => ['BillsController@byBuildingStore', $building->id]]) !!}
                <div class="form-group">
                    <label class="control-label col-sm-2" for="month">Выберите месяц: </label>
                    <datepicker language="ru" id="month" name="month" format="MM yyyy"></datepicker>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="bills">Шаблон счета: </label>
                    <div class="col-sm-10" style="padding: 0">
                        {!! Form::select('organization_id', $bills, null, ['class' => 'form-control', 'id' => 'bills', 'name' => 'bills']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="submit"></label>
                    <button type="submit" role="button" class="btn btn-info" id="submit">
                        Сформировать
                    </button>
                </div>
                {{--<textarea id="bill-template" name="bill-template">--}}

                {{--</textarea>--}}
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            theme: 'modern',
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'save table contextmenu directionality emoticons template paste textcolor'
            ],
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
        });
    </script>
    <script>
        var bus = new Vue({});

        var app = new Vue({
            el: '#app'
        });
    </script>
@endsection