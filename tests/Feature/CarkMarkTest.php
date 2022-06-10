<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class CardMarkTest extends TestCase
{
    use DatabaseTransactions;

    public function test_mark_card_function()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/cards/1/mark", [
            "_method" => "POST",
            "marked_message" => Str::random(20),
        ]);

        $response->assertJson([
            "message" => "You have marked this card.",
        ]);
    }
}
