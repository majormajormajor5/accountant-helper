@extends('layout')

@section('header')
    <div class="row">
        <div class="col-sm-12">
            <h3>Редактировать организацию</h3>
            <br>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if (count($errors) > 0)
                <alert id="alert-bag"></alert>
            @else
                <alert id="alert-bag" class="hidden"></alert>
            @endif
            <div class="hidden" id="errors">
                @if (count($errors) > 0)
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {!! Form::model($organization, ['route' => ['organizations.update', $organization->id],
                            'method' => 'PATCH',
                            'class' => 'form form-horizontal',
                           // '@submit' => 'isFormChanged',
                            'id' => 'organization-edit-form'
                            ])
            !!}

            <div class="form-group">
                <label class="control-label col-sm-2" for="name">Имя:</label>
                <div class="col-sm-10">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Имя организации']) }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-info">Coхранить</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        var bus = new Vue();

        new Vue({
            el: '#app'
            {{--,--}}
            {{--data: function () {--}}
                {{--return {--}}
                    {{--organization: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $organization }}"))),--}}
                    {{--organizationInitialData: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $organization }}")))--}}
                {{--}--}}
            {{--},--}}

            {{--created: function () {--}}
                {{--console.log(this.organization.name);--}}

            {{--},--}}

            {{--methods: {--}}
                {{--isFormChanged: function () {--}}
                    {{--for (property in this.organization) {--}}
                        {{--if (this.organization[property] != this.organizationInitialData[property]) {--}}
                            {{--return true;--}}
                        {{--}--}}
                    {{--}--}}
                    {{--var el = document.createElement('input');--}}
                    {{--el.setAttribute('type', 'hidden');--}}
                    {{--el.setAttribute('name', 'nothingChanged');--}}
                    {{--el.setAttribute('id', 'nothingChanged');--}}
                    {{--el.value = true;--}}
                    {{--if (!document.getElementById('nothingChanged')) {--}}
                        {{--document.getElementById('organization-edit-form').append(el);--}}
                    {{--}--}}
                    {{--return false;--}}
                {{--}--}}
            {{--}--}}
        });

        document.getElementsByClassName('alert-message-bag')[0].innerHTML += document.getElementById('errors').innerHTML;
    </script>
@endsection
