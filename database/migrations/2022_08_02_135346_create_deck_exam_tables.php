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
            $table->string("number_of_questions");
            $table->string("easy_answers");
            $table->string("good_answers");
            $table->string("hard_answers");
            $table->string("time_to_finish_exam");
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
