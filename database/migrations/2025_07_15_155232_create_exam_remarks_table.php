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
        Schema::create('exam_remarks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('exam_id');
            $table->integer('student_id');
            $table->integer('class_id');
            $table->string('remarks', 4096)->nullable();
            $table->integer('rank');
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
        Schema::dropIfExists('exam_remarks');
    }
};
