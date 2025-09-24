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
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('religion', 512);
            $table->string('qualification', 1024)->nullable();
            $table->string('gender', 32);
            $table->string('address', 1024)->nullable();
            $table->string('email', 1024)->nullable();
            $table->string('role', 512);
            $table->string('phone', 512)->nullable();
            $table->integer('salary')->nullable();
            $table->string('img_dir', 1024);
            $table->string('img_url', 1024);
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
        Schema::dropIfExists('employees');
    }
};
