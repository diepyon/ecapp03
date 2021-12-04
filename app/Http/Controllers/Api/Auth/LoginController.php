<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
 public function login(User $user,Request $request){
    if($user->where('email',$request->email)->first()==false || Hash::check($request->password,$user->where('email',$request->email)->first()->password==false)){
        dd('ログイン失敗');
    }else{
        dd('ログイン成功');
    }
    return response()->json(['login' => true], Response::HTTP_OK);
    //「login」的な処理が成功したらOKのレスポンスをvueに返す。名前や処理は未定。
    
 }
}