<template>
    <div>

        <h1>ID.{{ id }}詳細個別ページです</h1>

        <SingleImage v-bind:stock="stock" />

        <span class="" v-if="stock">
            <p>名前：{{stock.name}}</p>
            <p>金額：{{stock.fee}}</p>
            <p>ジャンル：{{stock.genre}}</p>
            <p>詳細：{{stock.detail}}</p>
            <p>投稿日:{{date}}</p>
        </span>

        <h2>↓後でキレイにする</h2>
        <!--         <button @click="stop" v-if="playing">停止</button>
 -->
        <!--         <button @click="play" v-else>再生</button>
 -->



        <wavesurfer :src="file" :options="options" id="wavesurfer" ref="surf"></wavesurfer>


        <button @click="hoge">メソッド発火</button>
        <!--  <button @click="play">波形再生</button> -->


    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";

    import SingleImage from '../layout/SingleImage'


    import * as fns from 'date-fns'

    import WaveSurferVue from "wavesurfer.js-vue";



    export default {
        props: {
            id: Number,
        },
        components: {
            Header,
            Footer,
            WaveSurferVue,
            SingleImage,
        },
        //title: 'タイトルどうしよう'
        data() {
            return {
                //title: 'タイトルどうしよう',
                stock: null,
                date: null,
                audio: new Audio('/storage/stock_sample/c9fea342.mp3'),
                playing: false,

                options: {},
                file: "/storage/stock_sample/c9fea342.mp3",
                
                filename:null
            }

        },
        methods: {
            play() {
                this.audio.play();
                this.playing = true
            },
            stop() {
                this.audio.pause();
                this.audio.currentTime = 0;
                this.playing = false

                //カーソルを先頭に戻すアクションも必要？
            },

            hoge(){
                this.wavesurfer.playPause(0,100)
                
            }
        },
        mounted() { //必ず通過するフック
            //console.log(this.id)
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/stocks/' + this.id)
                .then(response => {
                    this.stock = response.data.data
                    this.date = fns.format(new Date(this.stock.created_at), 'yyyy/MM/dd')

                    console.log(this.stock)
                })

            /*             this.player.on('ready', () => {
                            console.log('ready')
                        }) */ //エラーになるからいったん消してる

            this.player.on('ready', () => {
            console.log('ready')
            })



        },

        computed: {
            player() {
            return this.$refs.surf.waveSurfer
            }
        }
    };

</script>
