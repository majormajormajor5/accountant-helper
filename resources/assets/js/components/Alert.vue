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
        mounted() {
            this.show = true
        },

        data: function () {
            return {
                show: false,
                bus: new Vue()
            }
        },

        methods: {
            log: function () {
                console.log('clicked');
            },

            toggle: function () {
                console.log('got it');
                this.show = ! this.show;
            }
        },

        created: function() {
            bus.$on('new-message', this.toggle);
        }
    }
</script>
