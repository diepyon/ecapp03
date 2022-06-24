<template>
    <div>
        <h1>{{title}}</h1>
        <div class="stocks">
            <div class="" v-for="stock in stocks" :key="stock.id">
                <div class="stock_thumbnail">
                    <router-link :to="`stocks/` + stock.id">
                        <img @error="checkImgExist(stock.id)" id="stock.id" class="thumbnail" v-if="stock.genre=='image'"
                            :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                        <img  @error="checkImgExist(stock.id)" :id="stock.id" class="thumbnail" v-else-if="stock.genre=='video'"
                            :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                        <img  :id="stock.id" class="thumbnail" v-else-if="stock.genre=='audio'"
                             :src="'/storage/stock_thumbnail/audiothumbnail.png'">
                    </router-link>
                    <div class="genre_icon">
                        <span v-show="stock.genre=='image'">
                            <font-awesome-icon :icon="['fas', 'image']" /></span>
                        <span v-show="stock.genre=='video'">
                            <font-awesome-icon :icon="['fas', 'video']" /></span>
                        <span v-show="stock.genre=='audio'">
                            <font-awesome-icon :icon="['fas', 'music']" /></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            現在のページ：{{current_page}}<br>
            トータルページ数:{{length}}<br>
            トータル記事数:{{totalStocksPer}}<br>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><button class="page-link" @click="changePage(1)">
                            «</button></li>
                    <li class="page-item"><button class="page-link" @click="changePage(previous)">
                            ‹</button></li>

                    <li role="separator" class="page-item disabled bv-d-xs-down-none" v-if="current_page > 3"><span
                            class="page-link">…</span>
                    </li>

                    <li class="page-item"><button class="page-link" v-if="current_page -2 > 0"
                            @click="changePage(current_page - 2)">{{current_page -2}}</button></li>

                    <li class="page-item"><button class="page-link" v-if="current_page -1 > 0"
                            @click="changePage(current_page - 1)">{{current_page -1}}</button></li>

                    <li class="page-item active"><button class="page-link"
                            @click="changePage(current_page)">{{current_page}}</button></li>

                    <li class="page-item"><button class="page-link" v-if="current_page +1 <= length"
                            @click="changePage(current_page + 1)">{{current_page +1}}</button></li>

                    <li class="page-item"><button class="page-link" v-if="current_page +1 < length"
                            @click="changePage(current_page + 2)">{{current_page +2}}</button></li>

                    <li role="separator" class="page-item disabled bv-d-xs-down-none" v-if="current_page < length - 2">
                        <span class="page-link">…</span>
                    </li>

                    <li class="page-item" v-if="current_page +2 > 0"><button class="page-link"
                            @click="changePage(next)">
                            ›</button></li>

                    <li class="page-item"><button class="page-link" @click="changePage(length)">
                            »</button></li>
                </ul>
            </nav>
        </div>
        <Pagination />
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
        data() {
            return {
                title: 'Archive',
                stocks: null,
                current_page: null,
                length: null,
                parPage: null,
                totalStocksPer: null,
                pages: null,
                previous: null,
                next: null,
            }
        },
        mounted() {
            this.current_page = Number(this.$route.query.page) || 1
            this.showArchive()
        },
        methods: {
            checkImgExist(id) {//サムネイル画像がエラーになるときは代替え画像に置き換え
                const img = document.getElementById(id);
                img.setAttribute('src', '/storage/default_img/notfound.jpg');
            },
            async showArchive() {
                const result = await axios.get(`/api/stocks?page=${this.current_page}`);
                //↑この値を小コンポーネントに渡したいが、タイミングの問題ないのかnullになる
                //mountedに別の変数名でawaitなしで仕込めばいい？


                const stocks = result.data;
                this.stocks = stocks.data;
                this.parPage = stocks.meta.per_page //1ページ当たりの表示件数
                this.totalStocksPer = stocks.meta.total //全部でアイテムが何個あるか
                this.length = stocks.meta.last_page //総ページ数を取得             
                this.makePagenation()
            },
            makePagenation() {
                this.pages = []
                for (let i = 1; i < this.length + 1; i++) {
                    //ページ番号とリンク先をオブジェクトで追加
                    this.pages.push({
                        no: i,
                    })
                }

                //1個前のページ
                if (this.current_page !== 1) {
                    this.previous = this.current_page - 1
                } else {
                    this.previous = 1
                }

                //次のページ
                if (this.current_page !== this.length) {
                    this.next = this.current_page + 1
                } else {
                    this.next = this.length
                }
            },

            changePage(number) {
                this.current_page = number
                this.showArchive()

                window.history.pushState({
                        number
                    },
                    `Page${number}`,
                    `${window.location.origin}/stocks?page=${number}`
                )
                this.moveToTop()
            },
            moveToTop() {
                window.scrollTo({
                    top: 0,
                });
            },
        }
    }

</script>
<style scoped>
    .b-col img {
        max-width: 100%;
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
        margin: 8px;
    }

    .stock_thumbnail {
        position: relative;
    }

    /*サムネイルの左下に出るジャンル判別アイコン*/
    .stock_thumbnail:hover img {
        filter: brightness(50%);
        -webkit-transition: 0.1s ease-in-out;
        -moz-transition: 0.1s ease-in-out;
        -o-transition: 0.1s ease-in-out;
        transition: 0.1s ease-in-out;
    }

    .stocks {
        display: flex;
        flex-wrap: wrap;
        /* 親要素を無視して画面いっぱいに表示 */
        margin-right: calc(50% - 50vw);
        margin-left: calc(50% - 50vw);
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

</style>
