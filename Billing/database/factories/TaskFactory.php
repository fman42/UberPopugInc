<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ammount' => mt_rand(20, 40),
            'fee' => mt_rand(-20, -10),
            'completed' => mt_rand(0, 1)
        ];
    }
}
