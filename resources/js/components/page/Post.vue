<template>
    <div>
        <Header />
        <div id="form">
            <div class="form">
                <div class="form-title">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">作品名</label>
                        <input v-model="name" type="txt" class="form-control" id="exampleFormControlInput1"
                            placeholder="(例)富士山">
                        <p>{{name}}</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <span id="file_input_area">
                    <input type="file" class="form-control-file " id="myImage" v-on:change="fileSelected"
                        accept=".jpg,.jpeg,.png,.gif,.mp3,.wav,.m4a,.mp4,.mov">
                </span>
            </div>

            <div class=form-group>
                <select v-model="genreSelected">
                    <option v-for="genreOption in genreOptions" v-bind:value="genreOption.value"
                        :key="genreOption.value">
                        {{ genreOption.text }}
                    </option>
                </select>
                <span>Selected: {{ genreSelected }}</span>
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

                <textarea v-model="detail" class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
                <p style="white-space: pre-line;">{{ detail }}</p>
            </div>
            <div class="form-submit">
                <button type="submit" class="btn-primary"><i
                        class="fas fa-plus-square" @click="stockCreate">投稿する</i></button>
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
                name: null,
                detail: null,
                fileInfo: null,//inputfileの情報を格納する変数

                //ジャンル選択の配列
                genreSelected: 'image',
                genreOptions: [{
                        text: '画像',
                        value: 'image'
                    },
                    {
                        text: '映像',
                        value: 'video'
                    },
                    {
                        text: '音源',
                        value: 'audio'
                    },
                ],

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

                //true falseが入るバリデーションの配列
                validation: {
                    name: false,
                },

            }
        },
        methods: {
            clearForm() {
                //投稿が成功したらinputの内容をnullにするメソッドをここに定義すればいい？

            },

            fileSelected(event) {
                this.fileInfo = event.target.files[0]//選択されたファイルの情報を変数に格納
                //console.log(this.fileInfo)
            },

            stockCreate() { //投稿とボタンが押されたときに発動するメソッド

                let postData = {
                    //v-modelで取得した入力値の内容を変数に格納
                    name: this.name,
                    genre: this.genreSelected,
                    fee: this.feeSelected,
                    detail: this.detail,
                    fileInfo: this.fileInfo,
                    //ここにもfileInfoの情報が必要だと思う
                }

                axios.post('/api/stocks/create', postData) //api.phpのルートを指定。第2引数には渡したい変数を入れる（今回は配列postData=入力された内容）
                    .then(response => {
                        //ここに成功した時に行いたい処理を記載
                        alert('投稿できました');
                        console.log(response); //成功してたらデータが返ってくる
                        console.log(postData.fileInfo)

                    })
                    .catch(function (error) {
                        // handle error(axiosの処理にエラーが発生した場合に処理させたいことを記述)
                        alert('あかんかったわ、コンソール見て');
                        console.log(error);
                    })
            }
        },

        //バリデーションにまつわるフィルター
        filters: {
            titleValidator: {
                write: function (val) {
                    this.validation.name = !!val
                    return val
                }
            },
        },

    }

</script>
