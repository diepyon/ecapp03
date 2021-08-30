<template>
  <div>
    <Header />
    <h1>アーカイブです。</h1>
    <div v-for="stock in stocks" :key="stock.id">
      <h2>{{ stock.name }}</h2>
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
          hoge:null
          }
  },  
  mounted() {//必ず通過するフック
            axios.get('/api/stocks')
            .then(response => {
                this.stocks = response.data.data
                console.log(this.stocks)
            })
        },
};
</script>