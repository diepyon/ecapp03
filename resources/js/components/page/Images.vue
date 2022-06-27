<template>
    <div>
        <h1>{{title}}</h1>
        <h2 v-if="searchKeyword">「{{searchKeyword}}」の検索結果</h2>
        洗濯済みの選択肢をドロップダウンに表示したければ、javascriptで書かないとあかんかも<br>
        <div>
            <b-input-group class="search">
                <template #prepend>
                    <b-dropdown id="dropdown-1" text="すべての画像">
                        <b-dropdown-item>
                            <font-awesome-icon :icon="['fas', 'image']" />イラスト</b-dropdown-item>
                        <b-dropdown-item>写真</b-dropdown-item>
                    </b-dropdown>
                </template>

                <b-form-input v-model="keyword"  @keydown.enter="$refs.child.showArchive();searchKeyword = keyword">
                </b-form-input>

                <template #append>
                    <b-button @click="$refs.child.showArchive();searchKeyword = keyword" type="" id="btn-search" variant="primary">
                        <font-awesome-icon :icon="['fa', 'search']" />
                    </b-button>
                </template>
            </b-input-group>
        </div>
        <div class="stocks">
            <div class="" v-for="stock in stocks" :key="stock.id">
                <div class="stock_thumbnail">
                    <router-link :to="`stocks/` + stock.id">
                        <img @error="checkImgExist(stock.id)" :id="stock.id" class="thumbnail" :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                    </router-link>
                    <div class="genre_icon">
                        <span><font-awesome-icon :icon="['fas', 'image']" /></span>
                    </div>
                </div>
            </div>
        </div>

        <Pagination @stocksFromChild="getStocksFromChild" :genre="'image'" :keyword="this.keyword"  ref="child" />

    </div>
</template>
<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";
    import Pagination from "../layout/Pagination";

    export default {
        components: {
            Header,
            Footer,
            Pagination,
        },
        title: 'Image Archive',
        data() {
            return {
                title: '画像',
                stocks: null,
                current_page: null,
                lists: [],
                length: null,
                parPage: null,
                totalStocksPer: null,
                pages: null,
                previous: null,
                next: null,
                keyword: null,
                searchKeyword: null,
            }
        },
        mounted() {
            this.current_page = Number(this.$route.query.page) || 1
            this.keyword = this.$route.query.key



        },
        computed: {

        },
        methods: {
            getStocksFromChild(value) {
                //ページネーションコンポーネントから一覧すべきレコードを取得
                this.stocks = value
            },
            checkImgExist(id) { //サムネイル画像がエラーになるときは代替え画像に置き換え
                const img = document.getElementById(id);
                img.setAttribute('src', '/storage/default_img/notfound.jpg');
            },
        }
    };

</script>
<style scoped>
    .stock_thumbnail {
        position: relative;
        margin: 2.5px;
    }

    .genre_icon {
        color: #adb5bd99;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        cursor: pointer;
        position: absolute;
        bottom: 0%;
        left: 0%;
        margin: 8px;
    }

    /*サムネイルの左下に出るジャンル判別アイコン*/
    .stock_thumbnail {
        position: relative;
    }

    .stocks {
        display: flex;
        flex-wrap: wrap;
        /* 親要素を無視して画面いっぱいに表示 */
        margin-right: calc(50% - 50vw);
        margin-left: calc(50% - 50vw);
    }

    .stock_thumbnail:hover img {
        filter: brightness(50%);
        -webkit-transition: 0.1s ease-in-out;
        -moz-transition: 0.1s ease-in-out;
        -o-transition: 0.1s ease-in-out;
        transition: 0.1s ease-in-out;
    }

    /*レスポンシブデザイン*/

    @media screen and (min-width:769px) {

        /*** この中にPCのスタイル（769px以上） ***/
        .thumbnail {
            flex-grow: 1;
            object-fit: cover;
            max-height: 200px;
            max-width: 400px;
            margin: 0.2rem;
            border-radius: 4px;
        }
    }

    @media screen and (max-width:768px) {
        /*** この中にタブレットのスタイル（768px以下） ***/
        .thumbnail {
            flex-grow: 1;
            object-fit: cover;
            max-height: 150px;
            max-width: 300px;
            margin: 0.2rem;
            border-radius: 4px;
        }
    }

    @media screen and (max-width:599px) {

        /*** この中にスマホのスタイル（599px以下） ***/
        .thumbnail {
            flex-grow: 1;
            object-fit: cover;
            max-height: 100px;
            max-width: 200px;
            margin: 0.2rem;
            border-radius: 4px;
        }
    }

    .search {
        padding: .5em;
    }

</style>
