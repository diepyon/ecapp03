<template>
    <div v-if="isLoggedIn">
        <div>
            <p>{{ user.name }}</p>
            <p>{{ user.email }}</p>
            <template>
                <b-button variant="primary" v-b-modal.modal-center @click="$bvModal.show('modal-scoped')">編集</b-button>

                <b-modal centered id="modal-scoped">
                    <template #modal-header="{ close }">
                        <!-- Emulate built in modal header close button action -->
                        <h5 class="modal-title" id="profilemodalTitle">プロフィール編集</h5>
                        <b-button class="close" @click="close()">
                            <span aria-hidden="true">&times;</span>
                        </b-button>
                    </template>

                    <template>
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

                            <b-form-group id="" label="名前" description="">
                                <code>{{errorMessage.name}}</code>
                                <input v-model="userNewValue.name" @change="checkName" @blur="checkName" type="txt"
                                    class="form-control">
                            </b-form-group>

                            <b-form-group id="" label="メールアドレス" description="">
                                <code>{{errorMessage.email}}</code>
                                <input v-model="userNewValue.email" @change="checkEmail" @blur="checkEmail" type="email"
                                    class="form-control">
                            </b-form-group>

                            <b-form-group id="" label="パスワード" description="">
                                <!-- <code>{{errorMessage.name}}</code> -->
                                <input v-model="userNewValue.password" type="password" placeholder="変更しないときは未記入"
                                    class="form-control" autocomplete="off" value="">
                            </b-form-group>

                            <b-form-group id="" label="パスワード確認" description="">
                                <!-- <code>{{errorMessage.name}}</code> -->
                                <input v-model="userNewValue.passwordConfirm" type="password" placeholder="変更しないときは未記入"
                                    class="form-control" value="">
                            </b-form-group>

                            <b-alert show variant="warning" v-if="message=='nothing'">変更はありませんでした。</b-alert>
                        </b-form>
                    </template>

                    <template #modal-footer="{cancel }">
                        <b-button @click="update();" type="button" variant="primary">保存</b-button>

                        <b-button variant="secondary" @click="cancel()">
                            キャンセル
                        </b-button>

                    </template>
                </b-modal>
            </template>
            <button type="button" @click="logout">ログアウト</button>
            <b-alert show variant="success" v-if="message=='updated'">更新しました。</b-alert>
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
                    password: null,
                },
                userNewValue: {
                    id: null,
                    name: null,
                    email: null,
                    password: null,
                    passwordConfirm: null,
                    //file: null,
                },
                isLoggedIn: false,

                activeStatus: 'inactive',
                fileInfo: null,
                blobUrl: '/storage/user_icon/default_icon.jpg',

                message: null, //更新にまつわるメッセージ

                errorMessage: {
                    'name': null,
                    'email': null,
                    'password': null,
                },
            }
        },

        mounted() {
            this.getUserInfo()

            this.userNewValue.password = null
            this.userNewValue.passwordConfirm = null
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
                    this.userNewValue.file = this.fileInfo
                } else {
                    this.blobUrl = ""
                }
            },
            update() {
                // axios.get("/api/loginCheck")
                //     .then(response => {
                //         this.userNewValue.id = response.data.id
                //     })
                this.getUserInfo()

                let postData = new FormData()

                if (this.fileInfo) {
                    postData.append('files[0]', this.fileInfo) //files配列の先頭はthis.fileInfo
                    postData.append('extention', this.fileInfo.name.split('.').pop()) //拡張子を取得
                }

                postData.append('id', this.userNewValue.id)
                postData.append('name', this.userNewValue.name)
                postData.append('email', this.userNewValue.email)

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
                        }//最新版のユーザーアイコンを取得

                        this.userNewValue.name = this.user.name
                        this.userNewValue.email = this.user.email
                        this.userNewValue.id = response.data.id //カレントユーザーのIDを取得


                        //vuexでリアルタイムにユーザーの情報を更新（ヘッダーが変化を監視）
                        let userInfo = {
                            name: this.userNewValue.name,
                            email: this.userNewValue.name,
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
            checkEmail() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.email = Validate.email(this.userNewValue).message

                //モジュールから真偽を取得
                var result = Validate.email(this.userNewValue).result
                return result
            },
            checkName() {
                //モジュールからエラーメッセージを取得
                this.errorMessage.name = Validate.name(this.userNewValue).message

                //モジュールから真偽を取得
                var result = Validate.name(this.userNewValue).result
                return result
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
