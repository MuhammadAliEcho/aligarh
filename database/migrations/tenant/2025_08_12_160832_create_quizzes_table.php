<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();                          
            $table->string('title');                                
            $table->unsignedInteger('class_id');                 
            $table->unsignedInteger('section_id')->nullable();   
            $table->unsignedInteger('academic_session_id');      
            $table->date('date');                                   
            $table->unsignedInteger('teacher_id')->nullable();   
            $table->string('total_marks', 5);                       
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
        Schema::dropIfExists('quizzes');
    }
}
