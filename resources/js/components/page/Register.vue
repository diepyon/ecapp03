<template>
    <div>
        <Header />
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
                <button type="button" class="btn btn-primary" @click="register">投稿する</button>
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


                reg: /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}.[A-Za-z0-9]{1,}$/,//メールアドレスの形式を定義

                //配列にしたい                
                maxEmailLength: 10,
                errorMessage: {
                    'email': null,
                    'password': null,
                },
            }
        },
        mounted() { //必ず通過するフック
        },
        methods: {
            //ドラッグアンドドロップでファイルを選択できるようにもしたい

            //バリテーション
            checkEmail() {
                var n = ''//外に出した方がいい？？

                var n = this.form.email.length //emailの文字数を取得
                if (n == 0) {
                    this.errorMessage.email = "入力してください。"
                }else if(this.reg.test(this.form.email)==false){//メールアドレスの形式になっているかチェック
                    this.errorMessage.email = "メールアドレスの形式で入力してください。"
                }else{
                    this.errorMessage.email = ""
                }
                //document.getElementById('emailCheck').innerHTML = emailMessage
                if (this.errorMessage.email == "") {
                    var result = true
                }else{
                    var result = false
                } //emailの入力に問題がなければtrueを返す
                return (result)
            },
            checkPassword() {
                var n = ''
                var n = this.form.password.length //passwordの文字数

                if (n == 0) {
                    this.errorMessage.password = "パスワードを入力してください。"
                }else{
                    this.errorMessage.password = ""
                }

                if (this.errorMessage.password == "") {
                    var result = true
                }else{
                    var result = false
                } //passwordの入力に問題がなければtrueを返す
                return (result)

            },

            register() { //投稿とボタンが押されたときに発動するメソッド
                //投稿直前にも入力に不備がないかチェック
                var emailResult = this.checkEmail()
                var passwordResult = this.checkPassword()



                if (emailResult && passwordResult) {//check項目が全てtrueなら      
                    //バリデーション関数のreturnがどちらもtrueなら下記実行
                    axios.post('/api/register', this.form) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は配列postData=入力された内容）
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('投稿できました');
                            //console.log(response); //成功してたらデータが返ってくる
                            //投稿に成功したらv-modelを使って書くフォームをクリア
                            this.form.email = ""
                        })
                        .catch(function (error) {
                            // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                            alert('あかんかったわ、コンソール見て');
                            alert('このメールアドレスは既に登録されています。')//こうするしかない？
                            console.log(error);
                        })

                } else {
                    alert('入力に不備があります。')
                }
            }
        },
    }

</script>
