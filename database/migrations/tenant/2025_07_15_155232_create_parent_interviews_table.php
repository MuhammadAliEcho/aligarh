<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_interviews', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('student_id');
            $table->string('father_qualification', 12)->nullable();
            $table->string('mother_qualification', 12)->nullable();
            $table->string('father_occupation', 512)->nullable();
            $table->string('mother_occupation', 512)->nullable();
            $table->integer('monthly_income')->nullable();
            $table->string('other_job_father', 512)->nullable();
            $table->string('other_job_mother', 512)->nullable();
            $table->string('family_structure', 12)->nullable();
            $table->string('parents_living', 64)->nullable();
            $table->string('no_of_children', 1024);
            $table->mediumText('questions');
            $table->mediumText('questions_montessori');
            $table->string('remarks', 2048)->nullable();
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
        Schema::dropIfExists('parent_interviews');
    }
};
