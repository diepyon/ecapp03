<template>
    <div v-if="stock">
        <b-container class="bv-example-row">
            <b-row>
                <b-col sm="8">
                    <div class="" style="width:100%; display: flex;justify-content: center;align-items: center;">
                        <div style="magin-top:0;">
                            <b-button v-if="playing" style="margin-top:0;" @click="stop">
                                <font-awesome-icon :icon="['fa', 'stop']" />
                            </b-button>
                            <b-button v-else style="margin-top:0" @click="play">
                                <font-awesome-icon :icon="['fa', 'play']" />
                            </b-button>
                        </div>
                        <div style="width:100%; margin:0 0 0 .5em ;">
                            <wavesurfer :src="file" :options="options" id="wavesurfer" ref="surf"></wavesurfer>
                        </div>
                    </div>
                </b-col>
                <b-col sm="4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <font-awesome-icon :icon="['far', 'clock']" />{{stock.info.time}}</li>
                        <li class="list-group-item"><font-awesome-icon :icon="['fas', 'file']" />{{stock.fileType}}</li>
                        <li class="list-group-item"> {{stock.size}}</li>
                        <li class="list-group-item"> {{stock.info.bitrate}}bit</li>
                        <li class="list-group-item"> {{stock.info.sampringlate}}Hz</li>
                        <li class="list-group-item"> ￥{{stock.fee}}</li>
                    </ul>

                    <button class="btn btn-outline-secondary">
                        <font-awesome-icon :icon="['far', 'heart']" />お気に入りに保存</button>
                    <a class="btn btn-outline-secondary" :href="'/storage/stock_download_sample/'+stock.filename+'.mp3'"
                        role="button" download="">
                        <font-awesome-icon :icon="['fas', 'arrow-down']" />サンプルダウンロード</a>
                    <button class="btn btn-warning cart_button btn-lg btn-primary btn-lg btn-block">
                        <font-awesome-icon :icon="['fas', 'cart-arrow-down']" />カートに追加</button>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import WaveSurferVue from "wavesurfer.js-vue";
    export default {
        name: 'SingleAudio',
        props: ['stock'],
        components: {
            WaveSurferVue,
        },
        data() {
            return {
                playing: false,
                options: {
                    height: 90
                },
                file: "/storage/stock_sample/" + this.stock.filename + ".mp3",
                hoge: null
            }
        },
        mounted() {
             console.log(this.stock.info)
            //this.getAudioDuration()
        },
        methods: {
            play() {
                this.$refs.surf.waveSurfer.play()
                this.playing = this.$refs.surf.waveSurfer.isPlaying()
                this.$refs.surf.waveSurfer.on('finish', function () {
                    console.log('終わったから先頭に戻ってほしい')
                    //this.$refs.surf.waveSurfer.stop()とかを走らせたい
                    stop()
                })
            },
            stop() {
                this.$refs.surf.waveSurfer.stop()
                this.playing = this.$refs.surf.waveSurfer.isPlaying()
            },
            //バックエンドでやったほうがいい
            // getAudioDuration() {
            //     var audio = new Audio(); // audioの作成
            //     audio.src = '/storage/stock_download_sample/' + this.stock.filename + '.mp3'
            //     audio.load(); // audioの読み込み

            //     audio.addEventListener('loadedmetadata', function (e) {
            //         console.log(audio.duration); // 総時間の取得
            //     });
            // }
        },
        computed: {
            player() {
                return this.$refs.surf.waveSurfer
            }
        }
    };

</script>

<style scoped>
    ::v-deep .btn {
        margin-top: .5em
    }

    ::v-deep li.list-group-item>svg {
        margin-right: 0.2em;
    }

    ::v-deep .thumbnail {
        width: 100%;
    }

    .wrapper {
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
    }

</style>
