<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeckExamTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("deck_exams", function (Blueprint $table) {
            $table->id();
            $table->string("deck_name");
            $table->integer("number_of_questions");
            $table->integer("easy_answers");
            $table->integer("good_answers");
            $table->integer("hard_answers");
            $table->time("time_to_finish_exam");
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
        Schema::dropIfExists("deck_exams");
    }
}
