<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", [HomeController::class, "index"])->name("home");
Route::resource("decks", DeckController::class)->except(["create"]);
Route::post("/decks/{deck}/import", [DeckController::class, "import"])->name(
    "decks.import"
);
Route::get("/decks/{deck}/export", [DeckController::class, "export"])->name(
    "decks.export"
);
Route::resource("cards", CardsController::class)->except(["show"]);
Route::post("/cards/{card}/update_appear_on/", [
    CardsController::class,
    "updateAppearOn",
])->name("cards.update_appear_on");
