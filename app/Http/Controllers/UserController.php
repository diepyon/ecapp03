<?php

namespace App\Http\Controllers;
use App\Models\User;   //name,email,passwordだけfillableなので注意

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(User $user,$author_id)
    {
        $author =  User::where('id', $author_id)->first(); //作品の投稿者情報     
        return $author;//全部返してもいい？メールアドレス見られちゃう？
    }
    public function update(User $user,Request $request)
    {   
        //メールアドレス 被り対策ができてない
        
        $userRecord =  User::where('id', $request->id)->first();
        $beforeUpdatedAt = $userRecord->updated_at;//更新前のupdated_at

        //アイコンを最低限のサイズに縮小したい
        if($request->extention || $userRecord->icon == null){
            //ファイルがあるなら（拡張子があるなら）画像を投稿
            //ファイル名ランダムじゃなくてもユーザーIDで良い説            
            $filename = substr(bin2hex(random_bytes(8)), 0, 8);//ランダムなファイル名を定義
            $userRecord->update(['icon' => $filename.'.'.$request->extention,]);
            $request->file('files')[0]->storeAs('public/user_icon', $filename.'.'.$request->extention);//ランダムなファイル名.拡張子をファイル名に指定                 
        }elseif($request->extention){
            //ファイルありかつ既にアイコン設定済みなら上書き
            $filename = $userRecord->icon;
            $userRecord->update(['icon' => $filename.'.'.$request->extention,]);
            $request->file('files')[0]->storeAs('public/user_icon', $filename.'.'.$request->extention);//ランダムなファイル名.拡張子をファイル名に指定                 
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
    public function checkOldPassword(User $user,Request $request){
        $nowPassword =  User::where('id', $request->userId)->first()->password;

        if (Hash::check($request->oldPassword, $nowPassword)) {
            return '古いパスワードは合ってる';
        } else {
            return '古いパスワードが間違ってる';
        }

    }   
}