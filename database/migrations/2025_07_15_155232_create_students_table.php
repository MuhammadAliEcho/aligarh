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
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('session_id');
            $table->string('name', 256);
            $table->string('father_name');
            $table->string('gr_no', 64)->nullable();
            $table->integer('guardian_id');
            $table->string('religion', 1024)->nullable();
            $table->string('address', 1024)->nullable();
            $table->string('email', 1024)->nullable();
            $table->string('gender', 32);
            $table->string('phone', 64)->nullable();
            $table->string('guardian_relation', 512);
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_admission')->nullable();
            $table->date('date_of_enrolled');
            $table->string('place_of_birth', 512)->nullable();
            $table->string('receipt_no', 11)->nullable();
            $table->integer('tuition_fee');
            $table->integer('net_amount');
            $table->integer('discount');
            $table->integer('total_amount');
            $table->integer('late_fee');
            $table->string('last_school', 1024)->nullable();
            $table->string('seeking_class', 32)->nullable();
            $table->date('date_of_leaving')->nullable();
            $table->string('cause_of_leaving', 1024)->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('students');
    }
};
