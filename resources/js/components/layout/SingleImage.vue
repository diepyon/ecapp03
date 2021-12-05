<template>
    <div class="row">
        <div class="cool-sm-8">
            ここに画像
         {{val}}

        </div>
        <div class="col-sm-4">
            縦×横<br>
            mime<br>
            filesize<br>
            アスペクト比<br>
            値段<br>
            <button class="btn btn-outline-secondary"><i class="far fa-heart"></i>お気に入りに保存</button>
            <a class="btn btn-outline-secondary" href="" role="button" download=""><i
                    class="fas fa-arrow-down"></i>サンプルダウンロード</a>

            <button class="btn btn-warning cart_button btn-lg btn-primary btn-lg btn-block"><i
                    class="fas fa-cart-arrow-down">カートに追加</i></button>
        </div>
    </div>
</template>

<script>  
    export default {
        name:'SingleImage',
        props:['val']
    };

</script>
