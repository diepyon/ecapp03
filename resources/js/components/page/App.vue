<template>
    <div>
        <button type="button" class="btn btn-primary">Primary</button>

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
