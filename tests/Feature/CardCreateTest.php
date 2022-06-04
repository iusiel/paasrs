<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class CardCreateTest extends TestCase
{
    use DatabaseTransactions;

    public function test_show_create_card_page()
    {
        $response = $this->get("/cards/create?deck=1");

        $response->assertStatus(200);
    }

    public function test_show_create_card_page_with_no_deck()
    {
        $response = $this->get("/cards/create");
        $response->assertRedirect("/decks");
    }

    public function test_add_card()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/cards", [
            "question" => Str::random(20),
            "answer" => Str::random(20),
            "extra_information" => Str::random(20),
            "tags" => Str::random(20),
            "deck_id" => 1,
        ]);

        $response->assertJson([
            "message" => "Card created successfully.",
        ]);
    }

    public function test_add_card_with_reverse()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/cards", [
            "create_reverse_card" => "on",
            "question" => Str::random(20),
            "answer" => Str::random(20),
            "extra_information" => Str::random(20),
            "tags" => Str::random(20),
            "deck_id" => 1,
        ]);

        $response->assertJson([
            "message" => "Card created successfully.",
        ]);
    }
}
