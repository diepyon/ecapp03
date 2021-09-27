<template>
    <div>
        <Header />
        <h1>アーカイブです。</h1>
        <b-row>
            <b-col cols="6" sm="3" class="b-col" v-for="stock in stocks" :key="stock.id">
                <div> <a :href="`stocks/` + stock.id">
                        <p>{{ stock.name }}</p>
                        <p>{{ stock.genre }}</p>
                    </a>
                </div>
                <img :src="'/storage/stock_thumbnail/167.jpg'">
            </b-col>

        </b-row>
        <Footer />
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
        data() {
            return {
                stocks: null,
            }
        },
        mounted() { //必ず通過するフック
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/image')
                .then(response => {
                    this.stocks = response.data.data.reverse()
                    //console.log(this.stocks)
                })
        },
    };

</script>
<style scoped>
     .b-col img {
        max-width: 100%;
    }
</style>
