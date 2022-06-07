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
          
        
        

        // $userRecord->fill($request->only([
        //     'name', 'album', 'artist', 'image_path', 'release_date'
        // ]))->save();
    }    
}