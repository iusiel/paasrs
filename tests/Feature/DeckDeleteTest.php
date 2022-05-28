<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeckDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_delete_card_function()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/decks/1", [
            "_method" => "DELETE",
        ]);

        $response->assertJson([
            "message" => "Deck deleted successfully.",
        ]);
    }
}
