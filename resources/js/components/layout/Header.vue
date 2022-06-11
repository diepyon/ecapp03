<template>
    <header>
        <div>
            <b-navbar toggleable="lg" type="dark" variant="dark">
                <b-navbar-brand href="#">NavBar</b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <b-nav-item :class="{ active: $route.path === '/' }" to="/">Home</b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/about' }" to="/about">About</b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/stocks/create' }" to="/stocks/create">Post
                        </b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/stocks' }" to="/stocks">Archive</b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/login' }" to="/login">Login</b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/register' }" to="/register">Register
                        </b-nav-item>
                        <b-nav-item :class="{ active: $route.path === '/account' }" to="/account">アカウント</b-nav-item>
                    </b-navbar-nav>
                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">
                        <b-nav-item-dropdown v-if="isLoggedIn" right>
                            <!-- Using 'button-content' slot -->
                            <template #button-content>
                                <em>{{ userName}}</em>
                            </template>
                            <b-dropdown-item href="#">Profile</b-dropdown-item>
                            <b-dropdown-item href="#">Sign Out</b-dropdown-item>
                        </b-nav-item-dropdown>
                        <button v-else>ログイン</button>
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>
        </div>
    </header>
</template>
<script>
    export default {
        data() {
            return {
                userName: null,
                isLoggedIn: false,
            }
        },
        mounted() {
            axios.get("/api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true

                    //this.userName = localStorage.getItem('userName')
                    //email: localStorage.getItem("userEmail"),

                })
                .catch(error => {
                    this.isLoggedIn = false
                })

            //プロフィール更新時に認識させるから必要
            this.$store.watch(
                (state, getters) => getters.getUserName,
                (newValue, oldValue) => {
                    console.log('user changed! %s => %s', oldValue, newValue)
                    this.userName = newValue

                    //セッション切れ後初回ログインで通らないので2重処理
                    //これでうまくいったらノートに記載して
                    // if (this.userName) {
                    //     this.isLoggedIn = true
                    // } else {
                    //     this.isLoggedIn = false
                    // }

                    //vuexのユーザー名が変わったことを検知した上でサンクタムのログインチェック処理
                    axios.get("/api/loginCheck")
                        .then(response => {
                            this.isLoggedIn = true
                            let userInfo = {
                                name: this.$store.getters.getUserName,
                                //email: localStorage.getItem("userEmail"),
                            }
                            this.$store.commit("updateUser", userInfo);
                        })
                        .catch(error => {
                            this.isLoggedIn = false
                        })
                }
            )


            let userInfo = {
                name: localStorage.getItem("userName"),
                //email: localStorage.getItem("userEmail"),
            }
            this.$store.commit("updateUser", userInfo);

            console.log(localStorage.getItem("userName")) //localstorageの値をとる
        },

        beforeUpdate() {
            console.log('beforeupdate')

            //ここに書けばリロードじゃなくも行けるのでは？
            //これでいけるならmounted直下の axios.get("/api/loginCheck")はいらないんじゃないかな
            axios.get("/api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true
                    let userInfo = {
                        name: this.$store.getters.getUserName,
                        //email: localStorage.getItem("userEmail"),
                    }
                    this.$store.commit("updateUser", userInfo);
                })
                .catch(error => {
                    this.isLoggedIn = false
                })
        },

        computed: {

        },
        methods: {},
    };

</script>
