<template>
    <div>
        <Header />
        <h1>Login</h1>
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
            <b-button type="button" variant="primary" @click="onSubmit">ログイン</b-button>
        </b-form>
        <Footer />
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
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },

                errorMessage: {
                    'email': null,
                    'password': null,
                },
            }
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
                    axios.post('/api/login', this.form) //api.phpのルートを指定。第2引数には渡したい変数を入れる
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('送信（結果はコンソール見て）');
                        })
                        .catch(function (error) {
                            alert('あかんかったわ、コンソール見て');
                            console.log(error)
                        })
                } else {
                    alert('入力内容に不備があります。')
                }
            }
        }
    }

</script>
