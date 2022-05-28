<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("cards", function (Blueprint $table) {
            $table->id();
            $table->integer("deck_id");
            $table
                ->foreign("deck_id")
                ->references("id")
                ->on("decks");
            $table->text("question");
            $table->text("answer");
            $table->text("extra_information")->nullable();
            $table->text("tags")->nullable();
            $table->dateTime("appear_on");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("cards");
    }
}
