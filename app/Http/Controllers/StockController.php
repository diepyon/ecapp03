<?php

namespace App\Http\Controllers;

use App\Models\Stock;


use Illuminate\Http\Request;
use App\Http\Resources\StockResource;

use Illuminate\Support\Facades\Storage;//ファイルアップロード・削除関連

use Image;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Stock::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Stock $stock)
    {
        $id= $stock->all()->last()->id+1;//lastのID＋1で今からポストするこの投稿のIDを取得（同時に投稿がかかったらまずい？）    
        $extention = $request->form['extention']; //ファイルの拡張子を取得
        $request->file('files')[0]->storeAs('private/stocks', $id.'.'.$extention);//投稿のID.拡張子をファイル名に指定
        $stock->name = $request->form['name'];
        $stock->genre = $request->form['genre'];
        $stock->fee =  $request->form['fee'];
        $stock->detail =  $request->form['detail'];
        $stock->author_id = 1;//認証システムがまだできていないのでいったん仮で1としている
        $stock->path =$id.'.'.$extention;//投稿のID.拡張子をデータベースに登録
        $stock->save();

        //投稿時に走らせるとやっぱり重い。認証システム入れたら承認時に変換する方式にしたい
        if($request->form['genre']=='image'){
            $stock->ConversionImage($request->file('files')[0], $id);//画像なら変換
        }else if($request->form['genre']=='video'){
            $stock->ConversionVideo($request->file('files')[0],$id);//動画なら変換
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $stock = Stock::all();//stocksテーブルの情報をすべて取得
        return new StockResource($stock);//StockResourceにデータを渡してjsonに変換してもらおう
    }

    public function showImage()
    {
        $stock = Stock::where('genre', 'image')->get();//stocksテーブルからジャンルがimageのレコードをすべて取得
        dd($stock);

        return new StockResource($stock);//StockResourceにデータを渡してjsonに変換してもらおう
    }    

    public function single($stock_id)
    {//url上の数値を取得
        $stock = Stock::find($stock_id);//受け取った数値と一致するIDのレコードを取得
        return new StockResource($stock);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }

    /**
     * 投稿者に紐付く商品を取得するFunction
     */
    public function stocksByAuthorId($author_id)
    {
        $author = Stock::find($author_id);
        return new StockCollection($stock->stocks);
    }

    public function duration($fileInfo)
    {
        /*ここで取れたとして、どうやってビューに渡すのか*/
    }

}
