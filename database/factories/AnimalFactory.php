<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory {

    protected $model = Animal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        $photo = $this->getAnimal();
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'age' => $this->faker->numberBetween(0, 15),
            'race' => $this->faker->name(),
            'photo' => $photo
        ];
    }

    public function getAnimal() {
        $response = Http::withHeaders([
            'X-api-key' => '0bfddc72a7msh2662c50502e20cdp114875jsnee9e2c306c63',
        ])
            ->accept('application/json')
            ->get('https://api.thedogapi.com/v1/images/search');

        return $response->json()[0]['url'];
    }
}
