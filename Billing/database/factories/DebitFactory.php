<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DebitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ammount' => mt_rand(20, 40)
        ];
    }
}
