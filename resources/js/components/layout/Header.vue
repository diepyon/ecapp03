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
                        <b-nav-form>
                            <b-form-input size="sm" class="mr-sm-2" placeholder="Search"></b-form-input>
                            <b-button size="sm" class="my-2 my-sm-0" type="submit">Search</b-button>
                        </b-nav-form>
                        <b-nav-item-dropdown right>
                            <!-- Using 'button-content' slot -->
                            <template #button-content>
                                <em v-if="$store.state.name">{{ $store.state.name }}</em>
                            </template>
                            <b-dropdown-item href="#">Profile</b-dropdown-item>
                            <b-dropdown-item href="#">Sign Out</b-dropdown-item>
                        </b-nav-item-dropdown>
                        <button v-if="!$store.state.name">ログイン</button>
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
                //email: "",
                isLoggedIn: false,
            };
        },
        mounted() {
            axios.get("/api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true
                })
                .catch(error => {
                     this.isLoggedIn = false
                })
            let userInfo = {
                name: localStorage.getItem("userName"),
                email: localStorage.getItem("userEmail"),
            };
            this.$store.commit("updateUser", userInfo);
        },
        computed: {},
        methods: {},
    };

</script>
