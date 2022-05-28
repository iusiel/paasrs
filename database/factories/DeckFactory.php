<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class DeckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => Str::random(10),
            "number_of_cards_per_review" => "5",
            "hard_interval" => "1",
            "good_interval" => "4",
            "easy_interval" => "7",
        ];
    }
}
