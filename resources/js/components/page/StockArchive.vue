<template>
    <div>

        <h1>{{title}}</h1>
        <b-row>
            <b-col cols="6" sm="3" class="b-col" v-for="stock in stocks" :key="stock.id">
                <div class="stock_thumbnail">
                    <a :href="`stocks/` + stock.id">
                        <img v-if="stock.genre=='image'" :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                        <img v-else-if="stock.genre=='video'" :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                        <img v-else-if="stock.genre=='audio'" :src="'/storage/stock_thumbnail/audiothumbnail.png'">
                    </a>
                    <div class="genre_icon">
                        <span v-show="stock.genre=='image'"><font-awesome-icon :icon="['fas', 'image']" /></span>
                        <span v-show="stock.genre=='video'"><font-awesome-icon :icon="['fas', 'video']" /></span>
                        <span v-show="stock.genre=='audio'"><font-awesome-icon :icon="['fas', 'music']" /></span>                
                    </div>
                </div>
            </b-col>
        </b-row>

    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";

    export default {
        components: {
            Header,
            Footer,
        },
        title: 'Archive',
        data() {
            return {
                title: 'Archive',
                stocks: null,
            }
        },
        mounted() { //必ず通過するフック
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/stocks')
                .then(response => {
                    this.stocks = response.data.data.reverse()
                    console.log(this.stocks)
                })
        },
    };

</script>
<style scoped>
    .b-col img {
        max-width: 100%;
    }
    
.stock_thumbnail {
    position: relative;
}
.genre_icon {
    color: #adb5bd99;
    ;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    cursor: pointer;
    position: absolute;
    bottom: 0%;
    left: 0%;
    margin:8px;
}/*サムネイルの左下に出るジャンル判別アイコン*/
</style>
