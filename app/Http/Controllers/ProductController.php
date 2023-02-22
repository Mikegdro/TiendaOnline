<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller {

    public function data(Request $request) {
        $products = '';

        try {
            $products = DB::table('products')
                ->orderBy($request->input('orderby'), $request->input('ordertype'))
                ->get();
        } catch (\Exception $e) {
            $products = $e;
        }

        return $products;
    }
}
