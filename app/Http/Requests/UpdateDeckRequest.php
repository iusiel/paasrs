<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDeckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => [
                "required",
                "string",
                "max:255",
                Rule::unique("decks")->ignore($this->route("deck.id")),
            ],
            "number_of_cards_per_review" => "required|integer",
            "hard_interval" => "required|integer",
            "good_interval" => "required|integer",
            "easy_interval" => "required|integer",
        ];
    }
}
