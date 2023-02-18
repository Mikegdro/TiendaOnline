<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $products = Product::all();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products
    ]);
});

// Route::get('/pruebas', function () {

//     $categories = Category::all();
//     $category = $categories[fake()->numberBetween(0, count($categories) - 1)];
//     $name = $category->name;

//     $response = Http::get('https://dummyjson.com/products?q=laptop');
//     $response = $response->json();

//     dd($response);

//     $errors = array();

//     foreach($response['products'] as $prod) {
//          $product = new Product();
//          $product->name = $prod['title'];
//          $product->description = $prod['description'];
//          $product->price = $prod['price'];
//          $product->discount = $prod['discountPercentage'];
//          $product->rating = $prod['rating'];
//          $product->stock = $prod['stock'];
//          $product->brand = $prod['brand'];
//          $product->thumbnail = $prod['images'][0];
//          $product->categoryid = $category->id;

//          try {
//              $product->save();
//          } catch (\Exception $e) {
//              array_push($errors, $e);
//          }

//     }

//     dd($errors);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
