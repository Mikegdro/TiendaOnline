<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ProductController extends Controller {

    public function data(Request $request) {
        $products = '';

        try {
            $products = DB::table('products')
                ->orderBy($request->input('orderby'), $request->input('ordertype'))
                ->where('products.name', 'like', '%' . $request->input('search') . '%')
                ->get();
        } catch (\Exception $e) {
            $products = $e;
        }

        return $products;
    }

    public function upload(Request $request) {
        $product = '';

        $category = $request->input('category');
        $name = $request->input('name');

        if( $category ) {
            try {
                $categoryFound = DB::table('categories')
                    ->where('categories.name', "like", '%'.$category.'%')
                    ->get();

                if( $categoryFound ) {
                    $product = new Product($request->all());
                    $pathToImage = $this->saveImg($request->input('thumbnail'), $category, $name);
                    $product->thumbnail = $pathToImage;
                    $product->rating = 0;
                    $product->categoryid = $categoryFound[0]->id;
                    $product->save();
                }

            } catch(\Exception $e) {
                return $e;
            }
        }

        return $product;
    }

    public function saveImg($image, $category, $name) {
        try {

            $nombre = $category . $name . Carbon::now();
            $nombre = str_replace(' ', '-', $nombre);

            $image = Image::make($image);
            $image->save('storage/'.$nombre.'.jpg', 60);

        } catch(\Exception $e) {
            return $e;
        }

        return 'storage/'.$nombre.'.jpg';
    }

    public function seedDB(Request $request) {

        $response = Http::get('https://dummyjson.com/products?limit=100');

        $response = $response->json();


        try {
            foreach ($response['products'] as $value) {
                $prodCat = $value['category'];
                $result = $this->findCategory($prodCat);
                $catId = $result[0]->id;

                if( $catId ) {
                    $product = new Product();
                    $product->brand = $value['brand'];
                    $product->description = $value['description'];
                    $product->discount = $value['discountPercentage'];
                    $product->price = $value['price'];
                    $product->stock = $value['stock'];
                    $product->name = $value['title'];
                    $product->categoryid = $catId;
                    $product->thumbnail = $value['thumbnail'];
                    $product->rating = 0;
                    $product->save();
                }
            }
        } catch(\Exception $e) {
            return $e;
        }

    }

    private function findCategory($value) {
        try {
            $result = DB::table('categories')
                ->where('categories.name', "like", '%'.$value.'%')
                ->get();
        } catch (\Exception $e) {
            $result = $e;
        }

        return $result;
    }
}
