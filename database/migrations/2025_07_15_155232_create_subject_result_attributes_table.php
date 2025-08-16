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
        Schema::create('subject_result_attributes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('subject_id');
            $table->integer('exam_id');
            $table->integer('class_id');
            $table->double('total_marks');
            $table->string('attributes', 2048);
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_result_attributes');
    }
};
