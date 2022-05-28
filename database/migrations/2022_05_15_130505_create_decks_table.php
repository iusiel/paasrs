<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("decks", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("number_of_cards_per_review")->nullable();
            $table->integer("number_of_new_cards_per_review")->nullable();
            $table->integer("hard_interval")->nullable();
            $table->integer("good_interval")->nullable();
            $table->integer("easy_interval")->nullable();
            $table->integer("interval_hard_limit")->nullable();
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
        Schema::dropIfExists("decks");
    }
}
