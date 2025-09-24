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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('foreign_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('contact_no', 16)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('settings', 2048)->nullable();
            $table->string('user_type', 32);
            $table->timestamps();
            $table->boolean('active')->default(true);
            $table->string('role', 1028);
            $table->string('allow_content', 64);
            $table->json('allow_session')->nullable();
            $table->integer('academic_session');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
