<template>
    <div>
        <Header />
        <h1>RegisterRegister</h1>
        <div id="form">
            <div class="form">
                <div class="">
                    <div class="form-group">
                        <label for="">Email</label>
                        <code>{{errorMessage.email}}</code>
                        <input v-model="form.email" @change="checkEmail" @blur="checkEmail" type="email" class="form-control">
                        <p>{{form.email}}</p>
                    </div>

                    <div class="form-group">
                        <label for="">password</label>
                        <code>{{errorMessage.password}}</code>
                        <input v-model="form.password" @change="checkPassword" @blur="checkPassword" type="password" class="form-control">
                        <p>{{form.password}}</p>      
                    </div>
                </div>
            </div>

            <div class="form-submit">
                <button type="button" class="btn btn-primary" @click="register">登録</button>
            </div>
        </div>
        <Footer />
    </div>
</template>
<style scoped>
    .enter {
        border: 10px dotted powderblue;
    }

    .preview {
        margin: .5em;
    }

    /*ファイルプレビューエリアの余白*/
    .preview img,
    video {
        width: 100%;
        max-width: 500px;
    }

</style>
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
                //あらかじめ変数を定義してあげないとフロントが混乱する
                form:{
                    email: '',
                    password:'',
                },

                errorMessage: {
                    'email': null,
                    'password': null,
                },
            }
        },
        mounted() { //必ず通過するフック
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

            register() { //投稿とボタンが押されたときに発動するメソッド
                //投稿直前にも入力に不備がないかチェック
                var emailResult = this.checkEmail()
                var passwordResult = this.checkPassword()

                if (emailResult && passwordResult) {//check項目が全てtrueなら      
                    //バリデーション関数のreturnがどちらもtrueなら下記実行
                    axios.post('/api/register', this.form) //apiのルートを指定。第2引数には渡したい変数を入れる
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('登録できました。ダッシュボードに移管させたい！');
                        })
                        .catch(function (error) {
                            // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                            if(error.response.data.errors.email[0] == 'The email has already been taken.'){
                                console.log(error.response.data.errors.email[0])
                                alert('このメールアドレスはすでに登録されています。')
                            }else{
                                alert('あかんかったわ、コンソール見て');
                            }
                        })
                } else {
                    alert('入力に不備があります。')
                }
            }
        },
    }

</script>
