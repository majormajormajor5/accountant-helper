<template>
    <transition name="custom-classes-transition" enter-active-class="animated bounce" leave-to-class="animated fadeOutLeft">
       <div class="alert alert-danger" v-show="show">
           <a href="#" class="close" v-on:click="show = !show" onclick="console.log('ok')">×</a>
           <section id="alert-message-bag">

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
                notes: [1]
            }
        },

        created() {
        },

        methods: {
            log: function () {
                console.log('clicked');
            }
        }
    }
</script>
