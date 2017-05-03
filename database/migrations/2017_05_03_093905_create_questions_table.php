<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function ($table) {
            $table->increments('id');
            $table->mediumText('question');
            $table->timestamps();
        });

        Schema::create('answers', function ($table) {
            $table->increments('id');
            $table->integer('question_id');
            $table->integer('answer_in_question_id');
            $table->string('answer')->default('');
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
        Schema::drop('questions');
        Schema::drop('answers');
    }
}
