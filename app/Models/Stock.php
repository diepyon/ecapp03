<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Image;//画像リサイズライブラリ

//ffmpeg関連
use FFMpeg;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFilter;

class Stock extends Model
{
    protected $guarded = ['id'];
    
    use HasFactory;

    public function Stock()
    {
        return $this->hasMany(Stock::class);
        //これいるのか？なくても動いた
    }

    public static function ConversionImage($file, $id)
    {//画像変換メソッド
        /*----------
        縮小サイズで保存（ループ一覧用）
        ----------*/
        Image::make($file)->resize(800, 280, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path(('app/public/stock_thumbnail/'. $id.'.png'), 100));
        

        $imageWidth =Image::make($file)->width();//画像の横幅を取得
        $fontsize = $imageWidth*0.08;//画像に見合った透かしのフォントサイズを定義

        $sample = 'sample  sample  sample  sample  sample  sample  sample'."\n\n";
        for ($i =1; $i <= 5; $i++) {
            $sample = $sample.$sample;
        }//sample文字をたくさん挿入
            
        $x=100;
        $y=100;//挿入座標
        
        /*----------
        圧縮ありの透かし(singleページ用)
        ----------*/
        Image::make($file)
        ->text($sample, $x, $y, function ($font) use ($fontsize) {
            $font->file('assets/fonts/SawarabiGothic-Regular.ttf');
            $font->size($fontsize);        // 文字サイズ
            $font->color(array(255, 255, 255, 0.5));   // 文字の色
            $font->align('center'); // 横の揃え方（left, center, right）
            $font->valign('top');   // 縦の揃え方（top, middle, bottom）
            $font->angle(30);       // 回転（フォントが指定されていないと無視されます。）
        })
        ->resize(1000, 1000, function ($constraint) {
            $constraint->aspectRatio();
        })//singleページ用に圧縮
        ->save(storage_path('app/public/stock_sample/'. $id.'.png'), 100);


        /*----------
        圧縮なしの透かし(サンプルダウンロード用)
        ----------*/
        Image::make($file)
        ->text($sample, $x, $y, function ($font) use ($fontsize) {
            $font->file('assets/fonts/SawarabiGothic-Regular.ttf');
            $font->size($fontsize);        // 文字サイズ
            $font->color(array(255, 255, 255, 0.5));   // 文字の色
            $font->align('center'); // 横の揃え方（left, center, right）
            $font->valign('top');   // 縦の揃え方（top, middle, bottom）
            $font->angle(30);       // 回転（フォントが指定されていないと無視されます。）
        })
        ->save(storage_path('app/public/stock_download_sample/'. $id.'.png'), 100);
    }
    public static function ConversionVideo($file, $id)
    {
        //動画変換メソッド
 


        //動画サムネイルを生成
        $media = FFMpeg::fromDisk('local')
        //->open('private/stock_data/'.$uploaded_filename)
        ->open('private/stocks/'.$id.'.mp4')//mp4であることを全体にしてしまっているから、動画はmp4に変換する処理が手間に必要
        ->getFrameFromSeconds(1)//1フレーム目
        ->export()
        ->save('public/stock_thumbnail/'.$id.'.jpg');//ファイル名をjpgに変換
    }
}
