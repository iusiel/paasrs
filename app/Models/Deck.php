<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $table = "decks";

    protected $fillable = [
        'name',
        'number_of_cards_per_review',
        'number_of_new_cards_per_review',
        'hard_interval',
        'good_interval',
        'easy_interval',
        'interval_hard_limit',
    ];

    public function cards()
    {
        return $this->hasMany(Card::class, 'deck_id', 'id');
    }
}
