<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

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

        $key = env("OPEN_API_KEY");

        $categories = Http::withHeaders([
          'X-BLOBR-KEY' => $key
        ])->get('https://apis.blobr.app/f9g3z21kiqc0hz9s/en/v3/category/all');

        dd($categories->body());

        return [

        ];
    }
}
