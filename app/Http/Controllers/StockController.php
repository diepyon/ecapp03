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
    public function create(Request $request ,Stock $stock)
    {
        //dd(request()->all());

        //$file_name = request()->file->getClientOriginalName();
        //request()->file->storeAs('public/', $file_name);

        $stock->name = $request->input('name');
        $stock->genre = $request->input('genre');
        $stock->fee = $request->input('fee');
        $stock->detail =  $request->input('detail');

        $stock->author_id = 1;        
        $stock->path = 'test';
        $stock->save();

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
        $stock = Stock::all();
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