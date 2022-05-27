<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeckIndexTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_function()
    {
        $response = $this->get('/');

        $response->assertRedirect('/decks');
    }

    public function test_decks_index()
    {
        $response = $this->get('/decks');
        $response->assertStatus(200);
    }
}
