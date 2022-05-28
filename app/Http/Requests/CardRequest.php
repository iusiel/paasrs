<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            "deck_id" => "required|integer",
            "question" => "required|string|max:30000",
            "answer" => "required|string|max:30000",
            "extra_information" => "nullable|string|max:30000",
            "tags" => "nullable|string|max:30000",
        ];
    }
}
