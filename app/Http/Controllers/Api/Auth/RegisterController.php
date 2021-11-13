<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;

//use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            //'name' =>  $request->name,//初期値はemailの@マークより右側を入れたい
            'email' =>  $request->form['email'],
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['created' => true], Response::HTTP_OK);
    }
}