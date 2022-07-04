<?php

namespace App\Http\Controllers;
use App\Models\Subgenre;
use Illuminate\Http\Request;
use DB;

class SubgenreController extends Controller
{
    public function getSubgenre(Subgenre $subgenre,Request $request)
    {   
        //genreValueとかsubgenreValueってカラムから修正したい
        //もうこの時点でforeach回すなりして必要な情報だけをきれいな形でフロントに渡したい
        $genre  = $request->genre;
       
        return $subgenre->Where('genre',$genre)->get();
    }  
}
