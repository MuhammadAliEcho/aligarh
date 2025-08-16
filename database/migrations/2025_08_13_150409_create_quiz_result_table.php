<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_result', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quiz_id');
            $table->unsignedInteger('student_id');
            $table->string('obtain_marks', 5);
            $table->boolean('present');
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
        Schema::dropIfExists('quiz_result');
    }
}
