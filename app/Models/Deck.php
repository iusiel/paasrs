<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory;

    protected $table = "decks";
    protected $appends = ["earliest_activation_card"];

    protected $fillable = [
        "name",
        "number_of_cards_per_review",
        "hard_interval",
        "good_interval",
        "easy_interval",
        "randomize_order_of_questions",
    ];

    public function cards()
    {
        return $this->hasMany(Card::class, "deck_id", "id");
    }

    public function getEarliestActivationCardAttribute()
    {
        $card = $this->cards()
            ->select("appear_on")
            ->where("appear_on", ">=", date("Y-m-d H:i:s"))
            ->first();

        return !empty($card->appear_on)
            ? strtotime($card->appear_on)
            : strtotime("+1 year");
    }
}
