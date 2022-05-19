<?php

namespace App\Services;

class DefaultDeckSettings
{
    public static function get()
    {
        return [
            'number_of_cards_per_review' => 5,
            'number_of_new_cards_per_review' => 1,
            'hard_interval' => 1,
            'good_interval' => 4,
            'easy_interval' => 7,
            'interval_hard_limit' => 60
         ];
    }
}
