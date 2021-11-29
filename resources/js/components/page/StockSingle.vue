<template>
    <div>
        <Header />
        <h1>ID.{{ id }}詳細個別ページです</h1>

        <span class="" v-if="stock">
            <audio controls  :src="'/storage/stock_sample/'+stock.filename+'.mp3'"></audio>

            <p >名前：{{stock.name}}</p>
            <p >金額：{{stock.fee}}</p>
            <p >ジャンル：{{stock.genre}}</p>
            <p >詳細：{{stock.detail}}</p>
            <p >投稿日:{{date}}</p>   
        </span>
        <button  @click="stop" v-if="playing">停止</button>
        <button  @click="play" v-else>再生</button>
        <vue-wave-surfer :src="file" :options="options"></vue-wave-surfer>
        <button @click="play">波形再生</button>

        <font-awesome-icon icon="coffee" />
        <Footer />
    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";
    import Cursor from 'wavesurfer.js/dist/plugin/wavesurfer.cursor';

    import * as fns from 'date-fns'

    //import VueWaveSurfer from 'vue-wavesurfer'

    export default {
        props: {
            id: Number,
        },
        components: {
            Header,
            Footer,
            //VueWaveSurfer,
        },
        data() {
            return {
                stock:null,

                date:null,

                audio:new Audio('/storage/stock_sample/c9fea342.mp3'),
                playing:false,

                options: {
                plugins: [
                Cursor.create()
        ]                    
                },
                file: '/storage/stock_sample/f77fc4e0.mp3'                
            }
        },
         methods:{
             play(){
                 this.audio.play();
                 this.playing = true
             },
             stop(){
                this.audio.pause();
                this.audio.currentTime = 0;
                this.playing = false
             },
        },
        mounted() { //必ず通過するフック

            //console.log(this.id)
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/stocks/' + this.id)
                .then(response => {
                    this.stock = response.data.data
                    this.date = fns.format(new Date(this.stock.created_at), 'yyyy/MM/dd')

                    console.log(this.date)
                })
        },
    };

</script>
