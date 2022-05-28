<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CardDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_delete_card_function()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/cards/1", [
            "_method" => "DELETE",
        ]);

        $response->assertJson([
            "message" => "Card deleted successfully.",
        ]);
    }
}
