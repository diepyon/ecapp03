<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Resources\StockResource;

use Illuminate\Support\Facades\Storage;//ファイルアップロード・削除関連

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

        //dd($request->file('fileInfo'));
        //$request->file('fileInfo')->store('');
        // dd($request->file('files')[0]);

        $request->file('files')[0]->storeAs('public/img', 'hoge.png');
        //ファイル情報全体が配列なので番号を指定、storage/app/public/imgに保存する


        $request->file('files')[0]->storeAs('private/img', 'hoge.png');


       

        $stock->name = $request->form['name'];
        $stock->genre = $request->form['genre'];
        $stock->fee =  $request->form['fee'];
        $stock->detail =  $request->form['detail'];

        $stock->author_id = 1;
        $stock->path = 'test';
        $stock->save();

        //dd($request->file('files'));
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
}
