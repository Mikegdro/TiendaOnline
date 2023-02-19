<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/order', function(Request $request) {

    $products = '';

    try {
        $products = DB::table('products')
            ->orderBy($request->input('orderby'), $request->input('ordertype'))
            ->get();
    } catch (\Exception $e) {
        $products = $e;
    }

    return $products;
});

Route::get('/getData', function(Request $request) {

    $products = '';

    try {
        $products = DB::table('products')
            ->get();
    } catch (\Exception $e) {
        $products = $e;
    }

    return $products;
});
