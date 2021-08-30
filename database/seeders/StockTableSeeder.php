<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('stocks')->truncate(); //2回目実行の際にシーダー情報をクリア （ようわからん）
       DB::table('stocks')->insert([
           'name' => 'フィルムカメラ',
           'detail' => '1960年式のカメラです',
           'fee' => 200000,
           'path' => 'filmcamera.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'イヤホン',
           'detail' => 'ノイズキャンセリングがついてます',
           'fee' => 20000,
           'path' => 'iyahon.jpg',
            'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => '時計',
           'detail' => '1980年式の掛け時計です',
           'fee' => 120000,
           'path' => 'clock.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => '地球儀',
           'detail' => '珍しい商品です',
           'fee' => 120000,
           'path' => 'earth.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => '腕時計',
           'detail' => 'プレゼントにどうぞ',
           'fee' => 9800,
           'path' => 'watch.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'カメラレンズ35mm',
           'detail' => '最新式です',
           'fee' => 79800,
           'path' => 'lens.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'シャンパン',
           'detail' => 'パーティにどうぞ',
           'fee' => 800,
           'path' => 'shanpan.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'ビール',
           'detail' => '大量生産されたビールです',
           'fee' => 200,
           'path' => 'beer.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'やかん',
           'detail' => 'かなり珍しいやかんです',
           'fee' => 1200,
           'path' => 'yakan.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => '精米',
           'detail' => '米30Kgです',
           'fee' => 11200,
           'path' => 'kome.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'パソコン',
           'detail' => 'ジャンク品です',
           'fee' => 11200,
           'path' => 'pc.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'アコースティックギター',
           'detail' => 'ヤマハ製のエントリーモデルです',
           'fee' => 25600,
           'path' => 'aguiter.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'エレキギター',
           'detail' => '初心者向けのエントリーモデルです',
           'fee' => 15600,
           'path' => 'eguiter.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => '加湿器',
           'detail' => '乾燥する季節の必需品',
           'fee' => 3200,
           'path' => 'steamer.jpeg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'マウス',
           'detail' => 'ゲーミングマウスです',
           'fee' => 4200,
           'path' => 'mouse.jpeg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
       DB::table('stocks')->insert([
           'name' => 'Android Garxy10',
           'detail' => '中古美品です',
           'fee' => 84200,
           'path' => 'mobile.jpg',
           'author_id'=>1,
           'genre'=>'image',
       ]);
    }
}
