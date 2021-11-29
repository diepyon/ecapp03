<template>
    <div>
        <Header />
        
        <div id="form">
            <div class="form">
                <div class="">
                    <div class="form-group">
                        <label for="">作品名</label>
                        <code>{{errorMessage.name}}</code>
                        <input v-model="name" @change="checkName" @blur="checkName" type="txt" class="form-control">
                        <p>{{name}}</p>
                    </div>
                </div>
            </div>
            
            <code>{{errorMessage.file}}</code>
            <div class="drop_area" 
                v-show="previewArea==false" 
                @dragenter="dragEnter" 
                @dragleave="dragLeave" 
                @dragover.prevent
                @drop.prevent="dropFile" 
                :class="{enter: isEnter}">
              <div>販売する作品をドラッグ＆ドロップ</div>
              <div>png,jpg,mp4,mov,wav,mp3</div> 
                <label>
                    <span class="btn btn-primary">
                        選択
                        <input type="file" class="form-control-file " ref="file" @change="fileSelected"
                            accept=".jpg,.jpeg,.png,.gif,.mp3,.wav,.m4a,.mp4,.mov" style="display:none">
                    </span>
                </label>                
            </div>           
            
            <div class="form-group">
                <div v-show="previewArea" class="preview">
                    <div class="delete-mark" @click="deleteFile">×</div>
                    <img v-if="genre=='image'" :src="blobUrl">
                    <video v-on:loadedmetadata="videoInfo" controls v-if="genre=='video'" id="video" loop autoplay muted :src="blobUrl"></video>
                    <audio  v-on:loadedmetadata="audioInfo" v-if="genre=='audio'" id="audio" controls :src="blobUrl"></audio>
            </div>
            {{fileName}}
            </div>

            <div class=form-group>
                <label for="">ジャンル</label>
                <input type="txt" placeholder="自動選択されます" v-bind:value="genreString" readonly>
            </div>

            <div class=form-group>
                <label for="">販売価格</label>
                <select v-model="feeSelected">
                    <option v-for="feeOption in feeOptions" v-bind:value="feeOption.value" :key="feeOption.value">
                        {{ feeOption.text }}
                    </option>
                </select>
                <span>Selected: {{ feeSelected }}</span>
            </div>

            <div class="form-group">
                <label for="">商品説明</label>
                <code>{{errorMessage.detail}}</code>
                <textarea v-model="detail" @change="checkDetail" @blur="checkDetail" class="form-control" id=""
                    rows="5"></textarea>
                <p style="white-space: pre-line;">{{ detail }}</p>
            </div>
            <div class="form-submit">
                <button type="button" class="btn btn-primary" @click="stockCreate">投稿する</button>
            </div>
        </div>
        <Footer />
    </div>
</template>
<style scoped>
    .drop_area {
        color: gray;
        font-weight: bold;
        font-size: 1.2em;
        /*display: flex;*/
        justify-content: center;
        align-items: center;
        width: 500px;
        /*height: 300px;*/
        border: 5px solid gray;
        border-radius: 15px;
        max-width:100%;
        padding: 5em 0.5em;  
        text-align: center;
    }

    .enter {
        border: 10px dotted powderblue;
    }
    
    .delete-mark {
        top: -14px;
        right: -10px;
        font-size: 30px;
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
                name: '',
                detail: '',
                isEnter: false,//ドラッグアンドドロップフォームの変数初期値
                fileInfo: null, //inputfileの情報を格納する変数
                videDuration:null,

                fileType:null,

                fileName:'',

                deleteButton:false,

                //ジャンル選択の配列
                genre: '',
                genreString: '',
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
                //配列にしたい
                maxNameLength: 10,
                maxDetailLength: 120,
                errorMessage: {
                    'name': null,
                    'detail': null,
                    'file': null
                },
                blobUrl: null,
                previewArea: false,
            }
        },
        mounted() { //必ず通過するフック
        },
        
        methods: {
            videoInfo(){
                console.log(video)
                this.checkFile(video.duration)//プレビュー用のvideoタグから長さを取得
            },
            audioInfo(){
                console.log(audio)
                this.checkFile(audio.duration)
            },

            deleteFile() {
                this.fileInfo=null;
                this.previewArea = false
                this.deleteButton = false
                this.fileName=null
                this.$refs.file.value = null; //input fileクリア
                this.genre=null
                this.genreString=null
                },            
            
            
            //バリテーション
            checkName() {
                var n = ''
                var n = this.name.length //nameの文字数を取得
                if (n > this.maxNameLength) { //maxNameLengthはdata()内で定義
                    this.errorMessage.name = String(this.maxNameLength) + "文字以内で入力してください。"
                } else if (n == 0) {
                    this.errorMessage.name = "何か入力してください。"
                } else {
                    this.errorMessage.name = ""
                }
                //document.getElementById('nameCheck').innerHTML = nameMessage
                if (this.errorMessage.name == "") {
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
                    this.errorMessage.detail = String(this.maxDetailLength) + "文字以内で入力してください。"
                } else if (n == 0) {
                    this.errorMessage.detail = "何か入力してください。"
                } else {
                    this.errorMessage.detail = ""
                }
                //document.getElementById('detailCheck').innerHTML = errorMessage.detail
                if (this.errorMessage.detail == "") {
                    var result = true
                } else {
                    var result = false
                } //detailの入力に問題がなければtrueを返す
                return (result)
            },
            checkFile(duration) {
                this.previewArea = false //previewエリアのタグを非表示

                if (this.fileInfo == null||this.fileInfo == undefined) {
                    this.errorMessage.file = "選択してください"           
                } else if (this.fileInfo.size > 1073741824) { 
                    //1GBなら1073741824
                    this.errorMessage.file = "ファイルサイズ上限の1GBを超えています。"                
                } else if (this.fileInfo.size <= 0) {
                    this.errodMessage.file = "ファイル不正です。サイズが0KBです。"
                  
                } else if (this.genre=='video' && duration > 60) {//〇秒以上の動画なら
                    this.errorMessage.file = "投稿できる動画は60秒までです。"
        
                } else if (this.genre=='audio' && duration > 300) {//〇秒以上の動画なら
                    this.errorMessage.file = "投稿できる音源は5分までです。"
                           
                } else {
                    this.errorMessage.file = ""
                    this.previewArea = true //previewエリアのタグを表示
                }
                //document.getElementById('fileCheck').innerHTML = errorMessage.file
                if (this.errorMessage.file == "") {
                    var result = true
                } else {
                    var result = false
                    this.deleteFile()//ファイル情報をクリア
                } //fileの入力に問題がなければtrueを返す
                return (result)
            },

            dragEnter() {
                this.isEnter = true;
            },
            dragLeave() {
            this.isEnter = false;
            },
            dragOver() {
                console.log('DragOver')
            },
            dropFile(event) {
                this.fileInfo =[...event.dataTransfer.files][0]//選択されたファイルの情報を変数に格納

                this.fileType=this.fileInfo.type;

                if(this.fileType.match('video')||this.fileType.match('audio')||this.fileType.match('image')){ 
                    this.fileName = this.fileInfo.name 
                }else{
                    alert('そのファイル形式は選択できません。')
                    this.fileInfo = null
                }

                this.isEnter = false;

                if (this.fileInfo) {
                    this.errorMessage.file = null //ファイル未選択のバリデーションエラーが出てたら消す
                }

                if ( this.fileInfo != undefined) {
                    this.blobUrl = URL.createObjectURL(this.fileInfo) //選択されたファイルのURLを取得  
                } else {
                    this.blobUrl = ""
                }

                 this.genreSelect()
            }, 

            fileSelected(event) {
                this.fileInfo = event.target.files[0] //選択されたファイルの情報を変数に格納

                if (this.fileInfo) {
                    this.errorMessage.file = null //ファイル未選択のバリデーションエラーが出てたら消す
                }

                if (event.target.files[0] != undefined) {
                    this.blobUrl = URL.createObjectURL(this.fileInfo) //選択されたファイルのURLを取得 
                    this.fileName=this.fileInfo.name
                } else {
                    this.blobUrl = ""
                }
                this.genreSelect()
            },

            genreSelect(){
                let result = this.checkFile() //ファイルに問題がないかチェック

                if (result && this.fileInfo && this.fileInfo.type.match(
                        'image')) { //問題がないファイルが存在（選ばれていて）なおかつ画像なら
                    this.genre = 'image'
                    this.genreString = "画像"
                } else if (result && this.fileInfo && this.fileInfo.type.match(
                        'quicktime')) { //問題ないファイル存在が（選ばれていて）なおかつ動画なら
                    this.genre = 'video'
                    this.genreString = "映像"

                    //macのmovファイル？プレビューできないかもしれないイことを説明
                } else if (result && this.fileInfo && this.fileInfo.type.match(
                        'video')) { //問題ないファイル存在が（選ばれていて）なおかつ動画なら
                    this.genre = 'video'
                    this.genreString = "映像"

                } else if (result && this.fileInfo && this.fileInfo.type.match(
                        'audio')) { //問題ないファイル存在が（選ばれていて）なおかつ音源なら
                    this.genre = 'audio'
                    this.genreString = "音源"
                } else {
                    this.blobUrl = null
                    this.previewArea = false
                }   
            },

            stockCreate() { //投稿とボタンが押されたときに発動するメソッド
                //投稿直前にも入力に不備がないかチェック
                var nameResult = this.checkName()
                var detailResult = this.checkDetail()
                var fileResult = this.checkFile()


                if (nameResult && detailResult && fileResult) {//check項目が全てtrueなら
                    let postData = new FormData()
                    postData.append('files[0]', this.fileInfo) //files配列の先頭はthis.fileInfo
                    postData.append('form[extention]', this.fileInfo.name.split('.').pop()) //拡張子を取得
                    postData.append('form[name]', this.name)
                    postData.append('form[genre]', this.genre)
                    postData.append('form[fee]', this.feeSelected)
                    postData.append('form[detail]', this.detail)

                    //バリデーション関数のreturnがどちらもtrueなら下記実行
                    axios.post('/api/stocks/create', postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は配列postData=入力された内容）
                        .then(response => {
                            //ここに成功した時に行いたい処理を記載
                            alert('投稿できました');
                            //console.log(response); //成功してたらデータが返ってくる
                            //投稿に成功したらv-modelを使って書くフォームをクリア
                            this.name = ""
                            this.fileName=""
                            this.fileInfo =null
                            this.$refs.file.value = null; //input fileクリア
                            this.genre = ""
                            this.feeSelected = 1500
                            this.detail = ""
                            this.genreString = ""
                            this.previewArea = false
                        })
                        .catch(function (error) {
                            // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                            alert('あかんかったわ、コンソール見て');
                            console.log(error);
                        })
                } else {
                    alert('入力に不備があります。')
                    this.$refs.file.value = null; //input fileクリア
                }
            }
        },
    }

</script>
