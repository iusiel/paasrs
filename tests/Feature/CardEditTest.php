<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class CardEditTest extends TestCase
{
    use DatabaseTransactions;

    public function test_show_edit_card_page()
    {
        $response = $this->get('/cards/1/edit');
        $response->assertStatus(200);
    }

    public function test_edit_card_function()
    {
        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')->post('/cards/1', [
            '_method' => 'PUT',
            'question' => Str::random(20),
            'answer' => Str::random(20),
            'extra_information' => Str::random(20),
            'tags' => Str::random(20),
            'deck_id' => 1,
        ]);

        $response->assertJson([
            'message' => 'Card updated successfully.'
        ]);
    }
}
