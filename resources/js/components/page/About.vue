<template>
    <div>
        <p>{{ user.name }}</p>
        <p>{{ user.email }}</p>
        <button type="button" @click="logout">ログアウト</button>
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
                title: 'About',
                user: "",
            }
        },
        mounted() {
            axios.get("/api/user").then(response => {
                console.log(response.data)
                this.user = response.data;
            });
        },
        methods: {
            logout() {
                axios
                    .post("api/logout")
                    .then(response => {
                        console.log(response);
                        localStorage.removeItem("token");
                        this.$router.push("/login");
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }

</script>
