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
        Schema::create('admin_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('p_id');
            $table->integer('order_no');
            $table->string('label', 32);
            $table->string('icon', 64);
            $table->string('root', 128);
            $table->string('ctrl', 256);
            $table->string('func', 2048);
            $table->string('post_func', 1024);
            $table->string('options', 512);
            $table->string('type', 64);
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
        Schema::dropIfExists('admin_contents');
    }
};
