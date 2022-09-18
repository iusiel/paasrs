<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeckExam extends Model
{
    use HasFactory;

    protected $table = "deck_exams";
    protected $appends = [
        "easy_answers_percentage",
        "good_answers_percentage",
        "hard_answers_percentage",
    ];

    public function getEasyAnswersPercentageAttribute()
    {
        return round($this->easy_answers / $this->number_of_questions, 2) * 100;
    }

    public function getGoodAnswersPercentageAttribute()
    {
        return round($this->good_answers / $this->number_of_questions, 2) * 100;
    }

    public function getHardAnswersPercentageAttribute()
    {
        return round($this->hard_answers / $this->number_of_questions, 2) * 100;
    }
}
