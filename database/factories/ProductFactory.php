<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        $categories = Category::all();
        $category = $categories[fake()->numberBetween(0, count($categories) - 1)];

         $category = $categories[fake()->numberBetween(0, count($categories) - 1)];
         $name = $category->name;

         $response = Http::get('https://dummyjson.com/products?q=laptop');
         $response = $response->json();

         $errors = array();

//         foreach($response['products'] as $prod) {
//              $product = new Product();
//              $product->name = $prod['title'];
//              $product->description = $prod['description'];
//              $product->price = $prod['price'];
//              $product->discount = $prod['discountPercentage'];
//              $product->rating = $prod['rating'];
//              $product->stock = $prod['stock'];
//              $product->brand = $prod['brand'];
//              $product->thumbnail = $prod['images'][0];
//              $product->categoryid = $category->id;
//
//              try {
//                  $product->save();
//              } catch (\Exception $e) {
//                  array_push($errors, $e);
//              }
//
//         }

        // dd($errors);

        return [

        ];
    }
}
