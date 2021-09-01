<template>
    <div>
        <Header />
        <div id="form">
            <div class="form">
                <div class="form-title">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">作品名</label>
                        <code id="nameCheck"></code>
                        <input v-model="name" @change="checkName" @blur="checkName" type="txt" class="form-control"
                            id="">
                        <p>{{name}}</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <code id="fileCheck"></code>
                <span id="file_input_area">
                    <input type="file" class="form-control-file " id="myImage" @change="fileSelected" 
                        accept=".jpg,.jpeg,.png,.gif,.mp3,.wav,.m4a,.mp4,.mov">
                </span>
            </div>


            <div class=form-group>
                <label for="">ジャンル</label>
                {{genreString}}
            </div>

            <div class=form-group>
                <label for="exampleFormControlSelect1">販売価格</label>
                <select v-model="feeSelected">
                    <option v-for="feeOption in feeOptions" v-bind:value="feeOption.value" :key="feeOption.value">
                        {{ feeOption.text }}
                    </option>
                </select>
                <span>Selected: {{ feeSelected }}</span>
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea1">商品説明</label>
                <code id="detailCheck"></code>
                <textarea v-model="detail" @change="checkDetail" @blur="checkDetail" class="form-control"
                    id="exampleFormControlTextarea1" rows="5"></textarea>
                <p style="white-space: pre-line;">{{ detail }}</p>
            </div>
            <div class="form-submit">
                <button type="button" class="btn btn-primary" @click="stockCreate">投稿する</button>
            </div>
        </div>
        <Footer />
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
        data() {
            return {
                //あらかじめ変数を定義してあげないとフロントが混乱する
                name: '',
                detail: '',
                fileInfo: null, //inputfileの情報を格納する変数

                //ジャンル選択の配列
                genre: '',
                genreString:'',

                //金額選択の配列
                feeSelected: 1500,
                feeOptions: [{
                        text: '￥1,500',
                        value: 1500
                    },
                    {
                        text: '￥5,000',
                        value: 5000
                    },
                    {
                        text: '￥10,000',
                        value: 10000
                    },
                    {
                        text: '20000',
                        value: 20000
                    },
                ],

                maxNameLength: 10,
                maxDetailLength: 120,
            }
        },
        mounted() { //必ず通過するフック
           
        },
        methods: {
            //バリテーション
            checkName() {
                var n = ''
                var n = this.name.length //nameの文字数を取得

                if (n > this.maxNameLength) { //maxNameLengthはdata()内で定義
                    var nameMessage = String(this.maxNameLength) + "文字以内で入力してください。"
                } else if (n == 0) {
                    var nameMessage = "何か入力してください。"
                } else {
                    var nameMessage = ""
                }
                document.getElementById('nameCheck').innerHTML = nameMessage

                if (nameMessage == "") {
                    var result = true
                } else {
                    var result = false
                } //nameの入力に問題がなければtrueを返す
                return (result)

            },

            checkDetail() {
                var n = ''
                var n = this.detail.length //detailの文字数を取得

                if (n > this.maxDetailLength) {
                    var detailMessage = String(this.maxDetailLength) + "文字以内で入力してください。"
                } else if (n == 0) {
                    var detailMessage = "何か入力してください。"
                } else {
                    var detailMessage = ""
                }
                document.getElementById('detailCheck').innerHTML = detailMessage

            
                if (detailMessage == "") {
                    var result = true
                } else {
                    var result = false
                } //detailの入力に問題がなければtrueを返す
                return (result)
            },

            checkFile() {
                if (this.fileInfo==null) {
                    var fileMessage = "選択してください"
                } else {
                    var fileMessage = ""
                }

                document.getElementById('fileCheck').innerHTML = fileMessage

                if (fileMessage == "") {
                    var result = true
                } else {
                    var result = false
                } //fileの入力に問題がなければtrueを返す
                return (result)
            },


            fileSelected(event) {
                this.fileInfo = event.target.files[0] //選択されたファイルの情報を変数に格納

                console.log(this.fileInfo.type) //選択されたファイルタイプを取得


                //v-modelを文字列として出力しちゃうからNG
                if (this.fileInfo.type.match('image')) {
                    this.genre = 'image'
                    this.genreString="画像"
                    

                } else if (this.fileInfo.type.match('quicktime')) { //選択されたファイルが動画なら
                    this.genre = 'video'
                    this.genreString="映像"

                    //macのmovファイル？プレビューできないかもしれないイことを説明

                } else if (this.fileInfo.type.match('video')) { //選択されたファイルが動画なら
                    this.genre = 'video'
                    this.genreString="映像"


                } else if (this.fileInfo.type.match('audio')) { //選択されたファイルが音源なら
                    this.genre = 'audio'
                    this.genreString="音源"

                }
            },

            stockCreate() { //投稿とボタンが押されたときに発動するメソッド
                console.log(this.fileInfo)
                console.log('あとでけす')

                

                let postData = {
                    //v-modelで取得した入力値の内容を変数に格納
                    name: this.name,
                    genre: this.genre,
                    fee: this.feeSelected,
                    detail: this.detail,
                    fileInfo: this.fileInfo,
                    //ここにもfileInfoの情報が必要だと思う
                }

                console.log(`name:${this.checkName()}`) //nameのバリデーションメソッド発動&問題ないかをリターン
                console.log(`detail:${this.checkDetail()}`) //detailのバリデーションメソッド発動&問題ないかをリターン
                console.log(`file:${this.checkFile()}`)

            

                if (this.checkName() && this.checkDetail()) { //バリデーション関数のreturnがどちらもtrueなら下記実行

                    axios.post('/api/stocks/create', postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は配列postData=入力された内容）
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('投稿できました');
                            console.log(response); //成功してたらデータが返ってくる

                            //投稿に成功したらv-modelを使って書くフォームをクリア
                            this.name = ""
                            //ファイルもクリアしたい
                            this.genre = ""
                            this.feeSelected = 1500
                            this.detail = ""

                        })
                        .catch(function (error) {
                            // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                            alert('あかんかったわ、コンソール見て');
                            console.log(error);
                        })
                } else {
                    alert('入力に不備があります。')
                }


            }
        },
    }

</script>
