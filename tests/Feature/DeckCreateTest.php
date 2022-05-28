<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeckCreateTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_function()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/decks", [
            "name" => Str::random(20),
        ]);

        $response->assertJson([
            "message" => "Deck created successfully.",
        ]);
    }
}
