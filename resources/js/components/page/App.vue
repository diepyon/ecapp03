<template>
    <div class="container"><!--ここにcontiner設置するとヘッダーとかもぶつ切りにされる？-->
        <router-view />
    </div>
</template>

<script>
	import Header from '../layout/Header'
	import Footer from '../layout/Footer'

	//import Vue from 'vue'
	//import VueWaveSurfer from 'vue-wave-surfer'

	export default {
		components: {
			Header,
			Footer,
			//VueWaveSurfer,
		},
/* 		plugins: [
			'~/plugins/vue-wave-surfer'
		]	 */	
	}
</script>
