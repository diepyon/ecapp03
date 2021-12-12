<template>
    <div>
        <h1>{{title}}</h1>
    </div>
</template>

<script>
    import Header from '../layout/Header'
    import Footer from '../layout/Footer'

    export default {
        components: {
            Header,
            Footer,
        },
        title: 'About',

        data() {
            return {
                title: 'About'
            }
        }
    }

</script>
