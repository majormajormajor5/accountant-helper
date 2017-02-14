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
                            <tr v-if="organization" v-on:dblclick.ctrl="organization = !organization">
                                <td style="word-break: break-all!important;">
                                    {{ $organization->name }}
                                </td>
                                <td>
                                    <a href="#" type="button" role="button" class="btn btn-info edit-button" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover"><span class="glyphicon glyphicon-edit"></span> @desktop Редактировать &nbsp; &nbsp; @enddesktop</a>
                                </td>
                                <td>
                                    {!! Form::open(['url' => 'organizations/'. $organization->id, 'method'=> 'DELETE', '@submit.prevent' => 'deleteOrganization', 'v-ajaxform' => 'true']) !!}
                                        <button type="submit"
                                                role="button"
                                                class="btn btn-info"
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
        Vue.directive('ajaxform', {
            bind: function (el, binging, vnode) {
                el.addEventListener('submit', function (e) {
                    e.preventDefault();

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
                });
            }
        });

        var bus = new Vue();

        var app = new Vue({
           el: '#app',

           created: function () {
               bus.$on('modal-confirmed', this.submitAjaxRequest)
           },

           methods: {
               deleteOrganization: function () {

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
                                   this.emitAlert();
                               }
                               else {
                                   app.$emit('remove');
                               }
                           }
                       }
                   };

                   xmlhttp.open(window.lastReadyForSubmitForm.method, window.lastReadyForSubmitForm.url, true);
                   xmlhttp.send(window.lastReadyForSubmitForm.formData);
               }
           },

           data: function () {
               return {
                   message: 'message',
                   showModal: false,
                   organization: true
               }
           }
        });

        HTMLElement.prototype.removeClass = function(remove) {
            var newClassName = "";
            var i;
            var classes = this.className.split(" ");
            for(i = 0; i < classes.length; i++) {
                if(classes[i] !== remove) {
                    newClassName += classes[i] + " ";
                }
            }
            this.className = newClassName;
        };

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection