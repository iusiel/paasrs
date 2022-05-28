<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "deck_id" => 1,
            "question" => Str::random(10),
            "answer" => Str::random(10),
            "extra_information" => Str::random(10),
            "tags" => Str::random(10),
            "appear_on" => date("Y-m-d H:i:s", strtotime("yesterday")),
        ];
    }
}
