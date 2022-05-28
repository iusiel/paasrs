<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeckStudyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_show_study_page()
    {
        $response = $this->get("/decks/1");
        $response->assertStatus(200);
    }

    public function test_study_deck_without_cards()
    {
        $response = $this->get("/decks/2");
        $response->assertRedirect("/decks");
    }

    public function test_update_appear_on_function()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/cards/1/update_appear_on", [
            "id" => 1,
            "interval" => "easy",
        ]);

        $response->assertJson([
            "message" => "Card successfully updated.",
        ]);
    }
}
