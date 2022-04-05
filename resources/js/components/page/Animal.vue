<template>
    <div id="app">
        <h1>{{ $store.state.message }}</h1>
        <h2>{{$store.state.name}}</h2>
        <h2>{{ $store.state.email }}</h2>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                user: "",
                email: "hoge@fuga.com"
            }
        },
        mounted() {
            axios.get("/api/user")
                .then(response => {
                    this.user = response.data;
                    this.$store.commit("updateUser", this.user)
                })
                .catch(function (error) {
                    alert('ログインしろ！');
                })
            this.$store.commit("update");
        },
        computed: {

        },
        methods: {

        }
    };

</script>
