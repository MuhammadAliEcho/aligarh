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
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('religion', 512)->nullable();
            $table->string('qualification', 1024);
            $table->string('gender', 32);
            $table->string('address', 1024)->nullable();
            $table->string('email', 1024)->nullable();
            $table->string('phone', 512)->nullable();
            $table->string('salary', 128);
            $table->string('f_name')->nullable();
            $table->string('husband_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('image_dir', 1024)->nullable();
            $table->string('image_url', 1024)->nullable();
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
        Schema::dropIfExists('teachers');
    }
};
