<template>
    <div>
        <Header />
        <div class="container">
            <!--ここにcontiner設置するとヘッダーとかもぶつ切りにされる？-->
            <router-view />
        </div>
        <Footer />
    </div>
</template>

<script>
    import Header from '../layout/Header'
    import Footer from '../layout/Footer'
    import SingleImage from '../layout/SingleImage'
    //import wavesurfer from '../parts/VueWaveSurfer'

    //import Vue from 'vue'
    //import VueWaveSurfer from 'vue-wave-surfer'

    export default {
        components: {
            Header,
            Footer,
            SingleImage
        },
        /* 		plugins: [
        			'~/plugins/vue-wave-surfer'
        		]	 */
    }

</script>

<style scoped>
    header {
        margin-bottom: .5em;
    }

    footer {
        margin-top: .5em;
    }

</style>
