<template>
  <div>
    <Header />
    <h1>アーカイブです。</h1>
    <div v-for="stock in stocks" :key="stock.id">
      <a :href="`stocks/` + stock.id"><h2>{{ stock.name }}</h2></a>
    </div>
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
  data(){
    return{
          stocks:null,
          }
  },  
  mounted() {//必ず通過するフック
            //api.phpに記載された/stocksのルーティングのアクションを発動
            axios.get('/api/stocks')
            .then(response => {
                this.stocks = response.data.data
                console.log(this.stocks)
            })
        },
};
</script>