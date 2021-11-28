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
            <p >投稿日:{{stock.created_at}}</p>   
        </span>
        <button  @click="stop" v-if="playing">停止</button>
        <button  @click="play" v-else>再生</button>
        
        <Footer />
    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";

    export default {
        props: {
            id: Number,
        },
        components: {
            Header,
            Footer,
        },
        data() {
            return {
                stock:null,
                audio:new Audio('/storage/stock_sample/c9fea342.mp3'),
                playing:false,
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
                })
        },
    };

</script>
