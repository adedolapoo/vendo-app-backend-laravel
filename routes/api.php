<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', function (Request $request) {
   try{
       User::create($request->all());
       return response()->json(['message'=>'Registration successful']);
   }catch (\Exception $e){
       $message = "An error occurred";
       if( $e instanceof \Illuminate\Database\QueryException) $message= 'User already exist with  this email';
       return response()->json(['message'=>$message],401);
   }
});
