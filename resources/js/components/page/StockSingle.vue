<template>
    <div v-if="stock">

        <h1>ID.{{ id }}詳細個別ページです</h1>

        <SingleImage v-if="stock.genre=='image'" v-bind:stock="stock" />
        <SingleVideo v-if="stock.genre=='video'" v-bind:stock="stock" />
        <SingleAudio v-if="stock.genre=='audio'" v-bind:stock="stock" />

        <span class="" v-if="stock">
            <p>名前：{{stock.name}}</p>
            <p>金額：{{stock.fee}}</p>
            <p>ジャンル：{{stock.genre}}</p>
            <p>詳細：{{stock.detail}}</p>
            <p>投稿日:{{date}}</p>
        </span>

        <h2>↓後でキレイにする</h2>

        <div class="" style="width:100%; display: flex;justify-content: center;align-items: center;">
            <div style="magin-top:0;">
                <b-button v-if="playing" style="margin-top:0;" @click="stop">
                    <font-awesome-icon :icon="['fa', 'stop']" />
                </b-button>
                <b-button v-else style="margin-top:0" @click="play">
                    <font-awesome-icon :icon="['fa', 'play']" />
                </b-button>
            </div>
            <div style="width:100%; margin:0 0 0 .5em ;">
                <wavesurfer :src="file" :options="options" id="wavesurfer" ref="surf"></wavesurfer>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";
    import SingleImage from '../layout/SingleImage'
    import SingleVideo from '../layout/SingleVideo'
    import SingleAudio from '../layout/SingleAudio'
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
            SingleVideo,
            SingleAudio
        },
        //title: 'タイトルどうしよう'
        data() {
            return {
                stock: null,
                date: null,
                playing: false,
                options: {
                    height: 90
                },
                file: "/storage/stock_sample/c9fea342.mp3",
                filename: null,

                saisei: false,
            }
        },
        methods: {

            // play() {
            //     this.$refs.surf.waveSurfer.play()
            //     this.playing = this.$refs.surf.waveSurfer.isPlaying()

            //     this.hage = this.$refs.surf.waveSurfer.on('finish', function () {
            //         console.log('終わったから先頭に戻ってほしい')
            //         //this.$refs.surf.waveSurfer.stop()
            //         stop()
            //         return 'finish'
            //     })
            //     console.log(this.hage)
            //     //asynsc awaitを使ってfinishになったタイミングで変数を取得する
            //     //それをウォッチで監視して、任意の処理を走らせる
            // },
            finish: async function () {
                await this.$refs.surf.waveSurfer.on('finish', function () {
                    return '終わった'
                })

            },
            // play:async function() {
            //     this.$refs.surf.waveSurfer.play()//普通に再生
            //     this.saisei = true
            //     this.saisei = await this.finish()

            //     console.log(this.saisei)

            //     if(this.saisei=='終わった'){
            //         console.log('ここだ！！')
            //     }
            // },

            play() {
                this.$refs.surf.waveSurfer.play()//普通に再生

            },

            stop() {
                this.$refs.surf.waveSurfer.stop()
                this.playing = this.$refs.surf.waveSurfer.isPlaying()
            }

        },
        mounted() {
            //console.log(this.id)
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/stocks/' + this.id)
                .then(response => {
                    this.stock = response.data.data
                    this.date = fns.format(new Date(this.stock.created_at), 'yyyy/MM/dd')
                })
        },
        watch: {
            'playing'(newVal, oldVal) {
                // 残念ながら最後まで再生し終わった時には変数は変化してくれない
                console.log(newVal, '->', oldVal)
                if (newVal === true) {
                    console.log('再生中だぜ')
                } else if (newVal === false) {
                    console.log('再生が終わったぜ')
                }
            },
            'saisei'(newVal, oldVal) {
                console.log('変化あり')
                console.log(newVal, '->', oldVal)
            },
        },
        computed: {
            player() {
                return this.$refs.surf.waveSurfer
            }
        }
    };

</script>
<style scoped>
    ::v-deep .btn {
        margin-top: .5em
    }

    ::v-deep li.list-group-item>svg {
        margin-right: 0.2em;
    }

    ::v-deep .thumbnail {
        width: 100%;
    }

</style>
