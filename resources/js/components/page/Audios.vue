<template>
    <div>
        <h1>{{title}}</h1>
        <h2 v-if="searchKeyword">「{{searchKeyword}}」の検索結果</h2>
        <div>
            <b-input-group class="search">
                <template #prepend>
                    <b-dropdown :text="subGenreSelected.text">
                        <b-dropdown-item @click="selectSubgenre({value:null,text:'すべての音源'})">すべての音源</b-dropdown-item>
                        <b-dropdown-item v-for="subGenreOption in subGenreOptions" :key="subGenreOption.id"
                            @click="selectSubgenre(subGenreOption)">
                            {{subGenreOption.text}}
                        </b-dropdown-item>
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

        <!-- <div>
            <b-table :items="items"></b-table>
        </div> -->

        <table data-v-35119782="" role="table" aria-busy="false" aria-colcount="3" class="table b-table">
            <tbody role="rowgroup">
                <tr role="row" class="" v-for="stock in stocks" :key="stock.id">
                    <td aria-colindex="1" role="cell" class="">
                        <!-- <b-button v-if="playing" style="margin-top:0;" @click="stop">
                            <font-awesome-icon :icon="['fa', 'stop']" />
                        </b-button>
                        <b-button v-else style="margin-top:0" @click="play(stock.id)">
                            <font-awesome-icon :icon="['fa', 'play']" />
                        </b-button> -->
                    </td>
                    <td aria-colindex="2" role="cell" class=""> {{stock.name}}</td>
                    <td aria-colindex="3" role="cell" class="">
                        <div style="width:100%; margin:0 0 0 .5em ;">
                            <!-- <wavesurfer :src="'/storage/stock_sample/'+stock.filename+'.mp3'" :options="options"
                                id="'wavesurfer" :ref="surf"></wavesurfer> -->
                        </div>
                        {{stock.filename}}
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="width:100%; margin:0 0 0 .5em ;">
            <b-button v-if="playing" style="margin-top:0;" @click="stop">
                <font-awesome-icon :icon="['fa', 'stop']" />
            </b-button>
            <b-button v-else style="margin-top:0" @click="play()">
                <font-awesome-icon :icon="['fa', 'play']" />
            </b-button>
            <wavesurfer :src="'/storage/stock_sample/63337fec.mp3'" :options="options" id="aaa" ref="surf" >
            </wavesurfer>
        </div>


        <div class="">
            <div class="" v-for="stock in stocks" :key="stock.id">
                <div class="stock_thumbnail">
                    <router-link :to="`stocks/` + stock.id">
                        {{stock.name}}
                    </router-link>
                </div>
            </div>
        </div>
        <div class="text-center">
            <!-- 現在のページ：{{current_page}}<br>
            トータルページ数:{{length}}<br>
            トータル記事数:{{totalStocksPer}}<br> -->

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
    </div>
</template>
<script>
    import Header from "../layout/Header";
    import Footer from "../layout/Footer";
    import WaveSurferVue from "wavesurfer.js-vue";
    export default {
        components: {
            Header,
            Footer,
            WaveSurferVue,
        },
        title: 'Audio Archive',
        data() {
            return {
                title: '音源',
                stocks: null,
                subgenre: null,
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
                subGenreOptions: [],
                subGenreSelected: {
                    value: null,
                    text: 'すべての音源'
                },
                playing: false,
                options: {
                    height: 90
                },

                items: [{
                        age: 40,
                        first_name: 'Dickerson',
                        last_name: 'Macdonald'
                    },
                    {
                        age: 21,
                        first_name: 'Larsen',
                        last_name: 'Shaw'
                    },
                    {
                        age: 89,
                        first_name: 'Geneva',
                        last_name: 'Wilson'
                    },
                    {
                        age: 38,
                        first_name: 'Jami',
                        last_name: 'Carney'
                    }
                ]
            }
        },
        mounted() {
            window.addEventListener("popstate", this.handlePopstate)

            this.getSubgenre()
            this.current_page = Number(this.$route.query.page) || 1
            this.keyword = this.$route.query.key
            this.subGenreSelected.value = this.$route.query.subgenre

            if (this.$route.query.subgenre != undefined) {
                this.subgenreSelectedByUrl()
            }
            this.showArchive()
        },
        beforeDestroy() {
            window.removeEventListener("popstate", this.handlePopstate);
        },

        computed: {
            player() {
                return this.$refs.surf.waveSurfer
            }
        },
        methods: {
            play: async function () {
                this.$refs.surf.waveSurfer.play() //普通に再生
                
                //idを指定したい

            },
            finish: async function (playing) {
                console.log('finishメソッドに渡った時点では' + playing)
                var hage = this.$refs.surf.waveSurfer.on('finish', await
                    function () {
                        console.log('owata')
                    });
                return 'finish'
            },

            selectSubgenre(subGenreOption) {
                this.subGenreSelected = subGenreOption
            },

            handlePopstate() {
                this.current_page = Number(this.$route.query.page) || 1

                if (this.$route.query.key != undefined) {
                    this.keyword = this.$route.query.key
                } else {
                    this.keyword = ''
                }
                if (this.$route.query.subgenre != undefined) {
                    this.subGenreSelected.value = this.$route.query.subgenre
                    this.subgenreSelectedByUrl()
                } else {
                    this.selectSubgenre({
                        value: null,
                        text: 'すべての音源'
                    })
                }
                this.showArchive()
            },

            async showArchive() {
                this.searchKeyword = this.keyword
                let result = null
                result = await axios.get('/api/search', {
                    params: {
                        genre: 'audio',
                        subgenre: this.subGenreSelected.value,
                        key: this.keyword,
                        page: this.current_page,
                    }
                })
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
                this.current_page = number //受け取ったページ番号をthis.current_pageに格納

                let $url = null

                if (this.subGenreSelected.value && this.keyword) {
                    //console.log('サブジャンルの指定も、キーワードの指定もある')
                    $url =
                        `${window.location.origin}/audio?subgenre=${this.subGenreSelected.value}&key=${this.keyword}&page=${this.current_page}`
                } else if (this.subGenreSelected.value && !this.keyword) {
                    //console.log('サブジャンルの指定はあるが、キーワードの指定はない')
                    $url =
                        `${window.location.origin}/audio?subgenre=${this.subGenreSelected.value}&page=${this.current_page}`
                } else if (!this.subGenreSelected.value && !this.keyword) {
                    //console.log('サブジャンルもキーワードも指定がない')
                    $url = `${window.location.origin}/audio?page=${this.current_page}`
                } else if (!this.subGenreSelected.value) {
                    //console.log('サブジャンルの指定がない')
                    $url = `${window.location.origin}/audio?key=${this.keyword}&page=${this.current_page}`
                }

                window.history.pushState({
                        number
                    },
                    `Page${number}`,
                    $url
                )

                this.showArchive()
                this.moveToTop()
            },
            moveToTop() {
                window.scrollTo({
                    top: 0,
                });
            },
            checkImgExist(id) { //サムネイル画像がエラーになるときは代替え音源に置き換え
                const img = document.getElementById(id);
                img.setAttribute('src', '/storage/default_img/notfound.jpg');
            },
            getSubgenre() {
                axios.get("/api/stocks/getSubgenre?genre=audio")
                    .then(response => {
                        let subgenres = response.data
                        subgenres.filter(subgenre => {
                            this.subGenreOptions.push({
                                value: subgenre.subgenre,
                                text: subgenre.subgenreText
                            })
                        });
                    }) //サブジャンルの選択肢をデータベースから取得
            },
            subgenreSelectedByUrl() {
                axios.get("/api/stocks/subgenreSelectedByUrl?subgenre=" + this.$route.query.subgenre)
                    .then(response => {
                        //console.log('apiから取得したサブジャンルは')
                        //console.log(response.data.subgenre)

                        if (response.data.subgenre != undefined) {
                            this.subGenreSelected.value = response.data.subgenre
                            this.subGenreSelected.text = response.data.subgenreText
                        }
                    }) //サブジャンルの選択肢をデータベースから取得
            }
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
