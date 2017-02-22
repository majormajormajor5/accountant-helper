<template id="modal-template">
    <transition name="modal">
        <!--<div class="modal-mask">-->
            <!--<div class="modal-wrapper">-->
                <!--<div class="modal-container">-->

                    <!--<div class="modal-header">-->
                        <!--<slot name="header">-->
                            <!--default header-->
                        <!--</slot>-->
                    <!--</div>-->

                    <!--<div class="modal-body">-->
                        <!--<slot name="body">-->
                            <!--default body-->
                        <!--</slot>-->
                    <!--</div>-->

                    <!--<div class="modal-footer">-->
                        <!--<slot name="footer">-->
                            <!--<div>-->
                                <!--<div class="pull-left">-->
                                    <!--<button class="btn btn-info btn-lg" @click="declineAndCloseModal">Нет</button>-->
                                <!--</div>-->
                                <!--<div></div>-->
                                <!--<div class="pull-right">-->
                                    <!--<button class="btn btn-info btn-lg" @click="confirmAndCloseModal">OK</button>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</slot>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->

        <div class="vue-modal">
            <div class="vue-modal-content">
                <slot name="no-modal-header">
                    <div class="modal-header">
                        <slot name="header">
                            <span class="close" @click="closeModal">&times;</span>
                        </slot>
                    </div>
                </slot>

                <div class="modal-body">
                    <slot name="body">

                    </slot>
                </div>

                <div class="modal-footer">
                    <slot name="footer">
                        <div>
                            <div class="pull-left">
                                <button class="btn btn-info btn-lg" @click="declineAndCloseModal">&nbsp;Отмена&nbsp;</button>
                            </div>
                            <div></div>
                            <div class="pull-right">
                                <button class="btn btn-info btn-lg" @click="confirmAndCloseModal">&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</button>
                            </div>
                        </div>
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        methods: {
            closeModal: function () {
                this.$emit('close');
            },

            confirmAndCloseModal: function () {
                bus.$emit('modal-confirmed');
                this.closeModal();
            },
            
            declineAndCloseModal: function () {
                this.closeModal();
            }
            
            
        }
    }
</script>