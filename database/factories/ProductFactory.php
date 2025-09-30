<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
  public function definition(): array
  {
    $name = fake()->words(3, true);

    return [
      'name' => Str::title($name),
      'slug' => Str::slug($name),
      'description' => fake()->paragraph(3),
      'price' => fake()->randomFloat(2, 50000, 1000000),
      'stock' => fake()->numberBetween(10, 100),
      'image_url' => fake()->imageUrl(640, 480, 'product', true), // URL gambar acak
    ];
  }
}
