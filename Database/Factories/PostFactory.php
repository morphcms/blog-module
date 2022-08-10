<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Blog\Enums\PostStatus;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Blog\Models\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => Str::title($this->faker->words(3, true)),
            'summary' => Str::limit($this->faker->paragraph, 150, '.'),
            'status' => $this->faker->randomElement(PostStatus::values()),
        ];
    }

    public function published(): PostFactory
    {
        return $this->state(fn () => [
            'status' => PostStatus::Published,
        ]);
    }

    public function review(): PostFactory
    {
        return $this->state(fn () => [
            'status' => PostStatus::Review,
        ]);
    }

    public function draft(): PostFactory
    {
        return $this->state(fn () => [
            'status' => PostStatus::Draft,
        ]);
    }
}
