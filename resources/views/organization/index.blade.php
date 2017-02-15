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
{{--                            <tr v-if="organizations[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="deleteRow" id="{{ 'org' . $loop->index }}">--}}
                            <tr v-if="organizations[{{ $loop->index . '' }}]" v-on:dblclick.ctrl="organizations[{{ $loop->index . '' }}] = false" id="{{ 'org' . $loop->index }}">
                                <td style="word-break: break-all!important;">
                                    {{ $organization->name }}
                                </td>
                                <td>
                                    <a href="{{ url('organizations/' . $organization->id . '/edit') }}"
                                       type="button"
                                       role="button"
                                       class="btn btn-info edit-button"
                                    >
                                        <span class="glyphicon glyphicon-edit"></span> @desktop Редактировать &nbsp; &nbsp; @enddesktop
                                    </a>
                                </td>
                                <td>
                                    {!! Form::open(['url' => 'organizations/'. $organization->id,
                                                    'method'=> 'DELETE',
                                                    '@submit' => 'deleteOrganization'
                                                    ])
                                    !!}
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
               console.log(this.organizations);
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
//                                   console.log(document.getElementById('org0'));
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
                   console.log('deleted');
               }
           },

           data: function () {
               return {
                   message: 'message',
                   showModal: false,
                   organization: {"1": {"key": "value"}, "2": {"key": "value"}},
                   organizations: Object.assign({}, JSON.parse(replaceQuotHTMLEntitiesWithDoubleQuotes("{{ $organizations }}")))
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

        //Polyfill for closest() method
        if (window.Element && !Element.prototype.closest) {
            Element.prototype.closest =
                function(s) {
                    var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                        i,
                        el = this;
                    do {
                        i = matches.length;
                        while (--i >= 0 && matches.item(i) !== el) {};
                    } while ((i < 0) && (el = el.parentElement));
                    return el;
                };
        }

        //Polyfill for Object.assign() like Object.assign({}, ['a','b','c']); // {0:"a", 1:"b", 2:"c"}
        if (typeof Object.assign != 'function') {
            Object.assign = function(target, varArgs) { // .length of function is 2
                'use strict';
                if (target == null) { // TypeError if undefined or null
                    throw new TypeError('Cannot convert undefined or null to object');
                }

                var to = Object(target);

                for (var index = 1; index < arguments.length; index++) {
                    var nextSource = arguments[index];

                    if (nextSource != null) { // Skip over if undefined or null
                        for (var nextKey in nextSource) {
                            // Avoid bugs when hasOwnProperty is shadowed
                            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                                to[nextKey] = nextSource[nextKey];
                            }
                        }
                    }
                }
                return to;
            };
        }

        function simulateMouseEvent(eventName, element, options) {
            options = options || {};
            var event = new MouseEvent(eventName, options);

            return element.dispatchEvent(event);
        }
        
        function replaceQuotHTMLEntitiesWithDoubleQuotes(string) {
            return string.replace(/&quot;/g, '\"')
        }

        (function() {
            document.querySelector('.vue-hidden-container').removeClass('hidden');
        })();
    </script>
@endsection