<template>
    <div class="container"><!--ここにcontiner設置するとヘッダーとかもぶつ切りにされる？-->
        <router-view />
    </div>
</template>

<script>
	import Header from '../layout/Header'
	import Footer from '../layout/Footer'

	export default {
		components: {
			Header,
			Footer,
		}
	}
</script>
