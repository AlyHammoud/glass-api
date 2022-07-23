<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker; 

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
    public function definition()
    {
        
        return [
            'title' => $this->faker->title(),
            'slug' => $this->faker->slug(),
            'titleArabic' => $this->faker->titleMale(),
            'cover' => $this->faker->imageUrl(150, 150),
            // 'cover' => 'ProductsImages/JD2dJACAxY4oXdJY.jpeg',
            'briefDetails' => $this->faker->sentence(),
            'briefDetailsArabic' => $this->faker->sentence(),
            'fullDetails' => $this->faker->text(),
            'fullDetailsArabic' => $this->faker->text(),
            'service_id' => rand(1, 5),
            'user_id' => '1'
        ];
    }

    
}
