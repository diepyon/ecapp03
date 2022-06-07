<template>
    <!-- <div > -->
    <div v-if="!isLoggedIn">
        <h1>{{title}}</h1>
        <b-form>
            <b-form-group id="input-group-1" label="メールアドレス" description="">
                <code>{{errorMessage.email}}</code>
                <b-form-input @change="checkEmail" @blur="checkEmail" id="input-1" v-model="form.email" type="email"
                    placeholder="Enter email" required>
                </b-form-input>
            </b-form-group>
            <b-form-group id="input-group-2" label="パスワード" description="">
                <code>{{errorMessage.password}}</code>
                <b-form-input @change="checkPassword" @blur="checkPassword" id="input-2" v-model="form.password"
                    type="password" placeholder="password" required>
                </b-form-input>
            </b-form-group>
            <b-alert show variant="danger" v-if="message">{{message}}</b-alert>
            <b-button type="button" variant="primary" @click="onSubmit">ログイン</b-button>
        </b-form>
    </div>
</template>
<script>
    import Header from '../layout/Header'
    import Footer from '../layout/Footer'

    //バリデーションのモジュールを外部ファイルから読み込
    import * as Validate from '../../modules/validation.js'
    export default {
        components: {
            Header,
            Footer,
        },
        title: 'Login',

        data() {
            return {
                title: 'Login',
                message: null,

                //jumpTo: '/', //わざわざリダイレクト前のページを定義る必要がない説
                form: {
                    email: '',
                    password: '',
                },
                errorMessage: {
                    'email': null,
                    'password': null,
                },
                isLoggedIn: false,
            }
        },
        mounted() {
            //console.log('もともとアクセスしたかったページは' + this.$store.state.jumpTo)

            this.message = this.$store.state.message
            //this.isLoggedIn = localStorage.token //トークンがあればログインしている扱いにする

            axios.get("api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true
                    console.log('ログイン済み')
                    
                    if ((localStorage.getItem('jumpTo'))) {
                        this.$router.push(localStorage.getItem('jumpTo'))
                        localStorage.clear()
                    }else{
                        this.$router.push('/account')
                    }
                })
                .catch(error => {
                    console.log('未ログイン')
                    this.isLoggedIn = false
                })
        },

        methods: {
            checkEmail() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.email = Validate.email(this.form).message

                //モジュールから真偽を取得
                var result = Validate.email(this.form).result
                return result
            },
            checkPassword() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.password = Validate.password(this.form).message

                //モジュールから真偽を取得
                var result = Validate.password(this.form).result
                return result
            },
            onSubmit() {
                var emailResult = this.checkEmail()
                var passwordResult = this.checkPassword()

                if (emailResult && passwordResult) { //check項目が全てtrueなら  
                    axios.post('/api/login', this.form)
                        .then(response => {
                            let userInfo = {
                                id: response.data.user.id,
                                name: response.data.user.name,
                                email: response.data.user.email,
                                token: response.data.token,
                            }
                            this.$store.commit("checkLogin", userInfo)
                            this.$store.commit("resetState") //vuexに保存されているメッセージをリセット
                            

                            //ヘッダーのユーザーネームを読み込むため強制リロード
                            let jumpTo = localStorage.getItem('jumpTo')
                            console.log(jumpTo)
                            //localStorage.clear()

                            //vueだとなぜかうまくいかないZ
                            //let jumpTo = this.$store.state.jumpTo


                            console.log('もともとアクセスしたかったページは' + jumpTo)
                            this.$router.go(jumpTo) //もともとアクセスしたかったページ

                            // console.log('ホームにでも飛ばすか')
                            // this.$router.go(localStorage.getItem('/'))

                        })
                        .catch((error => {
                            //console.log(error)
                            this.message = 'ユーザー名またはパスワードが違います。'
                        }))
                } else {
                    alert('入力内容に不備があります。')
                }
            }
        },

    }

</script>
