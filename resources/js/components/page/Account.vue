<template>
    <div v-if="isLoggedIn">
        <div>
            <p>{{ user.name }}</p>
            <p>{{ user.email }}</p>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profilemodal">
                編集
            </button>
            <!-- Modal -->
            <div class="modal fade" id="profilemodal" tabindex="-1" role="dialog" aria-labelledby="profilemodalTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profilemodalTitle">プロフィール編集</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- x押してもフロント側は書き換わってしまうから別のモデル、変数にしたほうがいいかも -->
                            <div id="form">
                                <div class="form">
                                    <div class="parent" style="width:150px;">
                                        <img class="userIcon" :src="blobUrl" />
                                    <label style="display:initial;">
                                        <font-awesome-icon :class="activeStatus" @mouseover="beActive"
                                            @mouseleave="beInActive" class="child" :icon="['fa', 'camera']" />
                                        <input type="file" class="form-control-file " ref="file"
                                            @change="fileSelected" accept=".jpg,.jpeg,.png,.gif"
                                            style="display:none">
                                    </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="">名前</label>
                                        <!-- <code>{{errorMessage.name}}</code> -->
                                        <input v-model="user.name" type="txt" class="form-control">

                                    </div>
                                    <div class="form-group">
                                        <label for="">メールアドレス</label>
                                        <!-- <code>{{errorMessage.name}}</code> -->
                                        <input v-model="user.email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">パスワード</label>
                                        <!-- <code>{{errorMessage.name}}</code> -->
                                        <input v-model="user.password" type="password" placeholder="変更しないときは未記入"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">パスワード確認</label>
                                        <!-- <code>{{errorMessage.name}}</code> -->
                                        <input v-model="user.password" type="password" placeholder="変更しないときは未記入"
                                            class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" @click="logout">ログアウト</button>
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
        title: 'Account',

        data() {
            return {
                user: {
                    name: null,
                    email: null,
                    token: null,
                },
                isLoggedIn: false,

                activeStatus: 'inactive',
                fileInfo:null,
                blobUrl:'/storage/user_icon/default_icon.jpg',
            }
        },

        mounted() {
            //編集される可能性があるからそもそもここはデータベースから引っ張ってくるべきかも？？
            this.user.name = localStorage.userName
            this.user.email = localStorage.userEmail
            this.user.token = localStorage.token

            axios.get("/api/loginCheck")
                .then(response => {
                    this.isLoggedIn = true
                    console.log(response)
                })
                .catch(error => {
                    console.log(error)
                    this.isLoggedIn = false
                    this.$store.commit("message", 'ログインしてください。')

                    this.$router.push("/login") //ログイン画面にジャンプ
                })
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
                        localStorage.clear();
                        this.$store.commit("logout") //vuexの内容をリセット
                        this.$router.push("/login") //ログイン画面にジャンプ
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            fileSelected(event) {
                this.fileInfo = event.target.files[0] //選択されたファイルの情報を変数に格納

                // if (this.fileInfo) {
                //     this.errorMessage.file = null //ファイル未選択のバリデーションエラーが出てたら消す
                // }

                if (event.target.files[0] != undefined) {
                    this.blobUrl = URL.createObjectURL(this.fileInfo) //選択されたファイルのURLを取得 
                    this.fileName = this.fileInfo.name
                } else {
                    this.blobUrl = ""
                }
                console.log(this.blobUrl)   
            },
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
        color: white;
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

    .inactive {

    }
    .active {
        opacity: 0.8;
    }
</style>
