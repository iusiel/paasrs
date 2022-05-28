<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DeckCardsImportTest extends TestCase
{
    use DatabaseTransactions;

    public function test_import_success()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/decks/1/import", [
            "csv" => UploadedFile::fake()->createWithContent(
                "test.csv",
                "Test 1,Test 2,Test 3,Test 4"
            ),
        ]);

        $response->assertJson([
            "message" => "Cards imported successfully.",
        ]);
    }

    public function test_import_failure()
    {
        $response = $this->withHeader(
            "X-Requested-With",
            "XMLHttpRequest"
        )->post("/decks/1/import", [
            "csv" => UploadedFile::fake()->createWithContent(
                "test.csv",
                "Test 1,Test 2,Test 3"
            ),
        ]);

        $response->assertStatus(500);
    }
}
