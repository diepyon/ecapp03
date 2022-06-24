<template>
    <div>
        <h1>{{title}}</h1>
        <h2 v-if="searchKeyword">「{{searchKeyword}}」の検索結果</h2>
        洗濯済みの選択肢をドロップダウンに表示したければ、javascriptで書かないとあかんかも
        <div>
            <b-input-group class="search">
                <template #prepend>
                    <b-dropdown id="dropdown-1" text="すべての画像">
                        <b-dropdown-item>
                            <font-awesome-icon :icon="['fas', 'image']" />イラスト</b-dropdown-item>
                        <b-dropdown-item>写真</b-dropdown-item>
                    </b-dropdown>
                </template>

                <b-form-input v-model="keyword" v-on:keydown.enter="showArchive() ;changePage(1)">
                </b-form-input>

                <template #append>
                    <b-button @click="showArchive();changePage(1)" type="" id="btn-search" variant="primary">
                        <font-awesome-icon :icon="['fa', 'search']" />
                    </b-button>
                </template>
            </b-input-group>
        </div>

        <b-row>
            <b-col cols="6" sm="3" class="b-col" v-for="stock in stocks" :key="stock.id">
                <div class="stock_thumbnail">
                    <router-link :to="`stocks/` + stock.id">
                        <img :src="'/storage/stock_thumbnail/'+stock.filename+'.png'">
                    </router-link>
                    <div class="genre_icon">
                        <font-awesome-icon :icon="['fas', 'image']" />
                    </div>
                </div>
            </b-col>
        </b-row>
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

                    <li class="page-item" v-if="current_page +2 > 0"><button class="page-link" @click="changePage(next)">
                            ›</button></li>

                    <li class="page-item"><button class="page-link" @click="changePage(length)">
                            »</button></li>
                </ul>
            </nav>
        </div>

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

            this.showArchive()
        },
        computed: {

        },
        methods: {
            async showArchive() {
                let result = null
                this.searchKeyword = this.keyword
                if ((this.keyword)) {
                    result = await axios.get(
                        `/api/search?genre=image&key=${this.keyword}&page=${this.current_page}`)
                } else {
                    result = await axios.get(
                        `/api/images?page=${this.current_page}`)
                }
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
                if (this.keyword) {
                    this.current_page = number //受け取ったページ番号をthis.currentpageに格納

                    this.showArchive()
                    window.history.pushState({
                            number
                        },
                        `Page${number}`,
                        //search?genre=image&keyword=${this.keyword}&page=${this.current_page}`);
                        `${window.location.origin}/images?&key=${this.keyword}&page=${this.current_page}`
                    )
                } else {
                    this.current_page = number
                    this.showArchive()
                    window.history.pushState({
                            number
                        },
                        `Page${number}`,
                        `${window.location.origin}/images?page=${this.current_page}`
                    )
                }
                this.moveToTop()
            },
            moveToTop() {
                window.scrollTo({
                    top: 0,
                });
            },
        }
    };

</script>
<style scoped>
    .b-col img {
        max-width: 100%;
    }

    .stock_thumbnail {
        position: relative;
        margin: 2.5px;
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

    /*サムネイルの左下に出るジャンル判別アイコン*/
    .stock_thumbnail:hover img {
        filter: brightness(50%);
        -webkit-transition: 0.1s ease-in-out;
        -moz-transition: 0.1s ease-in-out;
        -o-transition: 0.1s ease-in-out;
        transition: 0.1s ease-in-out;
    }

    .search {
        padding: .5em;
    }

</style>
