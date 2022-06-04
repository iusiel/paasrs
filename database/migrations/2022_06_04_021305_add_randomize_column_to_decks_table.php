<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRandomizeColumnToDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("decks", function (Blueprint $table) {
            $table
                ->boolean("randomize_order_of_questions")
                ->after("easy_interval");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("decks", function (Blueprint $table) {
            $table->dropColumn("randomize_order_of_questions");
        });
    }
}
