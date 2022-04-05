<template>
    <div>
        <div v-if="isLoggedIn">
            <p>{{ user.name }}</p>
            <p>{{ user.email }}</p>
            <button type="button" @click="logout">ログアウト</button>
        </div>
        <div v-else>
            <p>{{ $store.state.message }}</p>
        </div>
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
                user: {
                    name: null,
                    email: null,
                    token: null,
                },
                isLoggedIn: false,
            }
        },
        mounted() {
            this.user.name = localStorage.userName
            this.user.email = localStorage.userEmail
            this.user.token = localStorage.token

            axios
                .get("api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true
                })
                .catch(error => {
                    this.isLoggedIn = false
                    this.$store.commit("message",'ログインしてください。')
                    this.$router.push("/login") //ログイン画面にジャンプ
                });
        },
        methods: {
            logout() {
                axios
                    .post("api/logout")
                    .then(response => {
                        localStorage.clear();
                        this.$store.commit("logout") //vuexの内容をリセット
                        this.$router.push("/login") //ログイン画面にジャンプ
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

        },
    }
</script>
