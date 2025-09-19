<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256);
            $table->integer('session_id');
            $table->string('father_name');
            $table->integer('class_id');
            $table->string('email', 1024)->nullable();
            $table->string('religion', 1024)->nullable();
            $table->string('gender', 32);
            $table->string('phone', 64)->nullable();
            $table->string('address', 1024)->nullable();
            $table->string('place_of_birth', 512)->nullable();
            $table->string('guardian_relation', 512);
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_visiting')->nullable();
            $table->string('last_school', 1024)->nullable();
            $table->string('seeking_class', 32)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('visitor_students');
    }
}
