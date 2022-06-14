<template>
    <div v-if="isLoggedIn">
        <div>
            <b-card no-body>
                <b-tabs pills card vertical>
                    <b-tab title="ユーザー情報" active>
                        <b-form id="form">
                            <div class="parent" style="width:150px;">
                                <img class="userIcon" :src="blobUrl" />
                                <label style="display:initial;">
                                    <font-awesome-icon :class="activeStatus" @mouseover="beActive"
                                        @mouseleave="beInActive" class="child" :icon="['fa', 'camera']" />
                                    <input type="file" class="form-control-file " ref="file" @change="fileSelected"
                                        accept=".jpg,.jpeg,.png,.gif" style="display:none">
                                </label>
                            </div>

                            <b-form-group id="" description="254文字まで">
                                <code>{{errorMessage.name}}</code>
                                <input v-model="user.name" @change="checkName" @blur="checkName" type="txt"
                                    class="form-control" placeholder="表示名">
                            </b-form-group>

                            <b-form-group id="" description="">
                                <code>{{errorMessage.email}}</code>
                                <input v-model="user.email" @change="checkEmail" @blur="checkEmail" type="email"
                                    class="form-control" placeholder="メールアドレス">
                            </b-form-group>
                            <b-alert show variant="success" v-if="message=='updated'">更新しました。</b-alert>
                            <b-alert show variant="warning" v-if="message=='nothing'">変更はありませんでした。</b-alert>
                            <b-button @click="update();" type="button" variant="primary">更新</b-button>
                        </b-form>
                    </b-tab>
                    <b-tab title="セキュリティ">
                        <template>
                            <div>
                                <code>{{errorMessage.currentPassword}}</code>
                                <b-form-input type="password" v-model="user.currentPassword" @change="checkCurrentPassword"
                                    @blur="checkCurrentPassword" placeholder="現在のパスワード">
                                </b-form-input>
                                <div class="mt-2"></div>
                            </div>
                            <div>
                                <code>{{errorMessage.newPassword}}</code>
                                <b-form-input type="password" @change="checkCurrentPassword"
                                    @blur="checkNewPassword" v-model="user.newPassword" placeholder="新しいパスワード">
                                </b-form-input>
                                <div class="mt-2"></div>
                            </div>
                            <div>
                                <code>{{errorMessage.newPasswordConfirm}}</code>
                                <b-form-input type="password" v-model="user.newPasswordConfirm"
                                     @change="checknewPasswordConfirm" @blur="checknewPasswordConfirm" placeholder="新しいパスワード再入力"></b-form-input>
                                <div class="mt-2"></div>
                            </div>
                        </template>
                        {{user.id}}
                        <b-button @click="passwordUpdate();" type="button" variant="primary">更新</b-button>
                    </b-tab>
                    <b-tab title="Tab 3">
                        <b-card-text>なにかしら</b-card-text>
                    </b-tab>
                </b-tabs>
            </b-card>
        </div>
    </div>
</template>

<script>
    import Header from '../layout/Header'
    import Footer from '../layout/Footer'

    //バリデーションのモジュールを外部ファイルから読み込み
    import * as Validate from '../../modules/validation.js'

    export default {
        components: {
            Header,
            Footer,
        },
        title: 'Account',

        data() {
            return {
                user: {
                    name: null,
                    email: null,
                    token: null,
                    currentPassword: null,
                    newPassword: null,
                    newPasswordConfirm: null,
                },

                isLoggedIn: false,

                activeStatus: 'inactive',
                fileInfo: null,
                blobUrl: '/storage/user_icon/default_icon.jpg',

                message: null, //更新にまつわるメッセージ

                errorMessage: {
                    'name': null,
                    'email': null,
                    'currentPassword': null,
                    'newPassword':null,
                    'newPasswordConfirm':null,
                },
            }
        },

        mounted() {
            this.getUserInfo()
            this.user.password = null
            this.user.passwordConfirm = null
        },
        methods: {
            beActive() {
                this.activeStatus = 'active'
            },
            beInActive() {
                this.activeStatus = 'inactive'
            },
            logout() {
                axios
                    .post("api/logout")
                    .then(response => {
                        localStorage.clear()
                        this.$store.commit("logout") //vuexの内容をリセット

                        //pushに変えてみた。headerのbefore mountedで監視してるから、セッション切れ後もワンチャンいける。
                        this.$router.push("/login") //ログイン画面にジャンプ
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            fileSelected(event) {
                this.fileInfo = event.target.files[0] //選択されたファイルの情報を変数に格納
                console.log(this.fileInfo)

                // if (this.fileInfo) {
                //     this.errorMessage.file = null //ファイル未選択のバリデーションエラーが出てたら消す
                // }

                if (event.target.files[0] != undefined) {
                    this.blobUrl = URL.createObjectURL(this.fileInfo) //選択されたファイルのURLを取得 

                    this.fileName = this.fileInfo.name //いらんかも
                    this.user.file = this.fileInfo
                } else {
                    this.blobUrl = ""
                }
            },
            update() {
                // axios.get("/api/loginCheck")
                //     .then(response => {
                //         this.user.id = response.data.id
                //     })


                this.getUserInfo()

                let postData = new FormData()
                if (this.fileInfo) {
                    postData.append('files[0]', this.fileInfo) //files配列の先頭はthis.fileInfo
                    postData.append('extention', this.fileInfo.name.split('.').pop()) //拡張子を取得
                }

                postData.append('id', this.user.id)
                postData.append('name', this.user.name)
                postData.append('email', this.user.email)


                //バリデーションを通過しないと更新させなくしたい
                axios.post("/api/account/update", postData)
                    .then(response => {
                        this.message = response.data //更新されたかどうかの結果を格納したメッセージ

                        if (this.message == 'updated') {
                            this.$bvModal.hide('modal-scoped')
                        } //変化があれば閉じる

                        this.getUserInfo() //ユーザー情報更新
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            getUserInfo() {
                axios.get("/api/loginCheck")
                    .then(response => {
                        this.isLoggedIn = true
                        let currentUser = response.data

                        this.user.name = currentUser.name
                        this.user.email = currentUser.email

                        //let icon = currentUser.icon

                        if (currentUser.icon) {
                            this.blobUrl = '/storage/user_icon/' + currentUser.icon
                        } //最新版のユーザーアイコンを取得

                        this.user.name = this.user.name
                        this.user.email = this.user.email
                        this.user.id = response.data.id //カレントユーザーのIDを取得

                        //vuexでリアルタイムにユーザーの情報を更新（ヘッダーが変化を監視）
                        let userInfo = {
                            name: this.user.name,
                            email: this.user.name,
                            //アイコンの情報もヘッダーに表示したいなら必要かも
                        }
                        this.$store.commit("checkLogin", userInfo)
                    })
                    .catch(error => {
                        //console.log(error)
                        this.isLoggedIn = false
                        //this.$store.commit("message", 'ログインしてください。')
                        this.$router.push("/login") //ログイン画面にジャンプ
                    })
            },

            //こいつら、まとめて一つのメソッドにできひん？引数にnameとかemailとかいれたらいいやん
            //なんかうまくいかんから、発火するメソッドをifで分岐したら良いと思う
            //引数にバリデーションチェックしたい項目の名前
            //if文に発動するモジュール

            checkEmail() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.email = Validate.email(this.user).message

                //モジュールから真偽を取得
                var result = Validate.email(this.user).result
                return result
            },
            checkName() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.name = Validate.name(this.user).message

                //モジュールから真偽を取得
                var result = Validate.name(this.user).result
                return result
            },


            //こいつらは1つのメソッドにまとめられる。
            //どの引数でどのフォームかを判定してifでメッセージの格納先を分ければ良い
            checkCurrentPassword() {
                this.errorMessage.currentPassword = Validate.checkhoge(this.user.currentPassword)
            },
            checkNewPassword() {
                this.errorMessage.newPassword = Validate.checkhoge(this.user.newPassword)
            },           
            checknewPasswordConfirm(){
                this.errorMessage.newPasswordConfirm = Validate.checkhoge(this.user.newPasswordConfirm)
            }, 

            //まとめた版のメソッド
            checkhogehoge(value){
                if(value == currentPassowrd){
                    this.errorMessage.currentPassword = Validate.checkhoge(this.user.currentPassword)
                }else if(value ==newPassword){
                    this.errorMessage.newPassword = Validate.checkhoge(this.user.newPassword)
                }else if(value == newPasswordConfirm){
                     this.errorMessage.newPasswordConfirm = Validate.checkhoge(this.user.newPasswordConfirm)
                }

            },



            passwordUpdate() {
                if (this.user.newPassword == this.user.newPasswordConfirm) {
                    console.log('パスワード一致')
                } else {
                    console.log('パスワード不一致')
                }
                axios.get("/api/account/checkOldPassword", {
                        params: {
                            oldPassword: this.user.oldPassword,
                            userId: this.user.id,
                        }
                    })
                    .then(response => {
                        console.log(response.data)
                    })
            },

            //まとめるチャレンジ
            validation(input) {
                // let hoge = input

                // this.errorMessage.hoge = Validate.hoge(this.userNewValue).message

                // //モジュールから真偽を取得
                // var result = Validate.input(this.userNewValue).result
                // return result
            }

        },
    }

</script>
<style scoped>
    .parent {
        position: relative;
    }

    .parent .child {
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        margin: 0;
        /*余計な隙間を除く*/
        padding: 0;
        color: rgba(99, 99, 99, 0.721);
        /*余計な隙間を除く*/
        font-size: 30px;
        /*サイズ*/
    }

    .parent img {
        width: 100%;
    }

    .userIcon {
        width: 150;
        height: 150px;
        border-radius: 50%;
        /*角丸*/
        object-fit: cover;
    }

    .inactive {}

    .active {
        opacity: 0.8;
    }

</style>
