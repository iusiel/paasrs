<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CardIndexTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cards_index_with_no_deck()
    {
        $response = $this->get('/cards');
        $response->assertStatus(200);
    }

    public function test_cards_index_with_deck()
    {
        $response = $this->get('/cards?deck=1');
        $response->assertStatus(200);
    }
}
