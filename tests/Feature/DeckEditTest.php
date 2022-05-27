<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeckEditTest extends TestCase
{
    use DatabaseTransactions;

    public function test_show_edit_deck_page()
    {
        $response = $this->get('/decks/1/edit');
        $response->assertStatus(200);
    }

    public function test_edit_deck_function()
    {
        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')->post('/decks/1', [
            '_method' => 'PUT',
            'name' => Str::random(20) . time(),
            'number_of_cards_per_review' => 5,
            'hard_interval' => 5,
            'good_interval' => 5,
            'easy_interval' => 5,
        ]);

        $response->assertJson([
            'message' => 'Deck updated successfully.'
        ]);
    }
}
