<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'role_id' => $this->faker->numberBetween($min = 1, $max = 5),
            'email' =>  $this->faker->email,
            'password' => $this->faker->numberBetween($min = 1, $max = 5),
            'direccion'=>$this->faker->address,
            'nacimiento'=>$this->faker->date($format = 'Y-m-d', $max = 'now'),
            'saldo'=>$this->faker->numberBetween($min = -100, $max = 100),
            'puntos'=>$this->faker->numberBetween($min = 10, $max = 1000),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
