<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;//ファイルのリネームで必要

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
    public static function ConversionVideo($extention, $id)
    {
        //動画変換メソッド
        $file = 'private/stocks/'.$id.'.mp4';//元ファイルのパス
        
        //mp4じゃなかったらmp4に変換（ていうか音消したいからどのみち無条件で変換した方がいいかも）
        if ($extention !='mp4') {//大文字MP4の場合どうなるのか　movならって条件の方がいいと思う
            $format = new \FFMpeg\Format\Video\X264('aac');
            $format->setAdditionalParameters(['-pix_fmt','yuv420p']);
            FFMpeg::open('private/stocks/'.$id.'.'.$extention)
            ->export()
            ->addFilter('-an')//音を消す
            ->inFormat($format)
            ->save($file);

        //movファイルを削除する処理も欲しい
        } else {
            //ここにmp4の時の音消し処理
            $format = new \FFMpeg\Format\Video\X264('aac');
            FFMpeg::open('private/stocks/'.$id.'.'.$extention)
            ->export()
            ->addFilter('-an')//音を消す
            ->inFormat($format)
            ->save('private/stocks/'.$id.'_tmp.mp4');//上書きできないので別名で保存
            
            Storage::delete($file);//上書きできないので元ファイルを削除
            Storage::move('private/stocks/'.$id.'_tmp.mp4', $file);//一時ファイルの名前を正しい名前に変更
        }

        //動画サムネイル画像を生成
        $media = FFMpeg::fromDisk('local')
        ->open($file)//mp4であることを全体にしてしまっているから、動画はmp4に変換する処理が手間に必要
        ->getFrameFromSeconds(1)//1フレーム目
        ->export()
        ->save('public/stock_thumbnail/'.$id.'.jpg');//ファイル名をjpgに変換


        //どんな透かしを入れるのかを定義
        $watermark = new WatermarkFilter(storage_path('app/private/watermark/logo.png'), [
        'position'=>'relative',
        'bottom' => 0,
        'left' => 0,
         ]);


         
        /*----------
        リサイズ
        ----------*/
        $height = $mediaStreams->get('height');// 解像度(縦)を取得
        $width = $mediaStreams->get('width');// 解像度(横)を取得
        $aspect=$stock->gcd($width, $height);//アスペクト比を取得

        
        if ($height >= 2160) {
            $size ='4k'; //サイズを指定
            $this->resizeVideo($size, $id, $width, $height);//サイズの名前,ファイルid,横幅,高さ
        }
        
        //fullHD以上なら1080pに変換
        if ($height >= 1080) {
            $size='hd';
            $this->resizeVideo($size, $id, $width, $height);
        }
        
        //480以上ならSD画質に変換
        if ($height >= 480) {
            $size='sd';
            $this->resizeVideo($size, $id, $width, $height);
        }

         
        /*----------
        圧縮ありの透かし(singleページ用)
        ----------*/

        
        /*----------
        圧縮なしの透かし(サンプルダウンロード用)
        ----------*/
        FFMpeg::open($file)//mp4形式のデータを読み込み
            ->export()
            ->inFormat(new \FFMpeg\Format\Video\X264('aac'))
            ->addFilter($watermark)
            ->save('stock_download_sample/'. $id.'.mp4');
    }
    


 

    //ビデオリサイズ関数。
    private function resizeVideo($size, $id, $width, $height)
    {
        $file = 'private/stocks/'.$id.'.mp4';//元ファイルのパス
        
        $media = FFMpeg::open($file);
        $mediaStreams = $media->getStreams()[0];

        // 解像度(縦)を取得
        $height = $mediaStreams->get('height');
        // 解像度(横)を取得
        $width = $mediaStreams->get('width');
        //アスペクト比を取得
        $aspect=$stock->gcd($width, $height);

        //指定されたサイズにより解像度を決定
        if($size=="4k"){
            $resolution = 2160;
        }elseif($size=="hd"){
            $resolution = 1080;
        }elseif($size=="sd"){
            $resolution = 480;
        }

        FFMpeg::open('private/stock_data/'.$stock_filename.'.mp4')->addFilter(function ($filters) use($width,$height,$resolution){
            $changeWidth = round($width*$resolution/$height);
            if ($changeWidth%2==1) {
                $changeWidth = $changeWidth+1;
            }
            $filters->resize(new \FFMpeg\Coordinate\Dimension($changeWidth, $resolution));
        })
        ->export()
        ->toDisk('local')
        ->inFormat(new \FFMpeg\Format\Video\X264('aac'))
        ->save(ここのパスちゃんとかく);
    }



        //サイズ別にビデオをまとめて変換
        public function videoEncode($height,$width, $stock_filename,$stock_path){
            //4以上なら4kに変換(ファイルサイズ制限がdocker側にあるので動作確認できていない)
            if($height >= 2160){
                $size ='4k'; //サイズを指定
                $this->resizeVideo($size,$stock_filename,$width,$height);
                
            }                
            
            //fullHD以上なら1080pに変換
            if($height >= 1080){
                $size='hd';
                $this->resizeVideo($size,$stock_filename,$width,$height);
                                                  
            }                     
            
            //480以上ならSD画質に変換
            if($height >= 480){
                $size='sd';
                $this->resizeVideo($size,$stock_filename,$width,$height);
                
            }
        }    
    
}