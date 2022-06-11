<?php

namespace App\Http\Controllers;
use App\Models\User;   //name,email,passwordだけfillableなので注意

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(User $user,$author_id)
    {
        $author =  User::where('id', $author_id)->first(); //作品の投稿者情報     
        return $author;//全部返してもいい？メールアドレス見られちゃう？
    }
    public function update(User $user,Request $request)
    {   
        $userRecord =  User::where('id', $request->id)->first();
 
        $beforeUpdatedAt = $userRecord->updated_at;//更新前のupdated_at

        if($request->extention){
            //ファイルがあるなら（拡張子があるなら）画像を投稿
            $filename = substr(bin2hex(random_bytes(8)), 0, 8);//ランダムなファイル名を定義
            $userRecord->update(['icon' => $filename.'.'.$request->extention,]);
            $request->file('files')[0]->storeAs('public/user_icon', $filename.'.'.$request->extention);//ランダムなファイル名.拡張子をファイル名に指定
            //古い画像は削除したい            
        }

        $result = $userRecord->fill($request->only([
            'name', 'email'
        ]))->update();

        $afterUpdatedAt = $userRecord->updated_at; //更新後のupdated_at

        if($beforeUpdatedAt == $afterUpdatedAt ) {//updated_atに差分があるかをリターン
            return 'nothing';
        }else{
            return 'updated';
        }
    }    
}