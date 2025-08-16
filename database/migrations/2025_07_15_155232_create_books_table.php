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
        Schema::create('books', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 2048);
            $table->string('author', 1024)->nullable();
            $table->string('edition', 1024)->nullable();
            $table->string('publisher', 1024)->nullable();
            $table->integer('qty')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('rate')->nullable();
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
        Schema::dropIfExists('books');
    }
};
