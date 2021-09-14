<template>
    <div>
        <Header />
        <h1>ID.{{ id }}詳細個別ページです</h1>
        <span class="" v-if="stock">
            <p >名前：{{stock.name}}</p>
            <p >金額：{{stock.fee}}</p>
            <p >ジャンル：{{stock.genre}}</p>
            <p >詳細：{{stock.detail}}</p>
             <p >投稿日:{{stock.created_at}}</p>
        </span>
        <Footer />
    </div>
</template>

<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";

    export default {
        props: {
            id: Number
        },


        components: {
            Header,
            Footer,
        },
        data() {
            return {
                stock: null,
            }
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
