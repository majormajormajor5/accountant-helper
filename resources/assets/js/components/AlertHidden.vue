<template>
    <transition name="custom-classes-transition" enter-active-class="animated bounce" leave-to-class="animated fadeOutLeft">
        <div class="alert alert-danger" v-show="show">
            <a href="#" class="close" v-on:click="show = !show">×</a>
            <section class="alert-message-bag">

            </section>
        </div>
    </transition>
</template>

<script>
    export default {
        data: function () {
            return {
                show: false,
                bus: new Vue(),
                message: true
            }
        },

        methods: {
            log: function () {
                console.log('clicked');
            },

            toggle: function () {
                this.show = true;
            },

            hide: function () {
                this.show = false;
                this.message = false;
            },

            open: function () {
                this.message = true;
            }
        },

        created: function() {
            bus.$on('new-message', this.toggle);
            bus.$on('close', this.hide);
            bus.$on('open', this.open);
        }
    }
</script>
