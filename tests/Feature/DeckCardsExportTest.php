<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeckCardsExportTest extends TestCase
{
    use DatabaseTransactions;

    public function test_export()
    {
        $response = $this->get("/decks/1/export");
        $response->assertStatus(200);
    }
}
