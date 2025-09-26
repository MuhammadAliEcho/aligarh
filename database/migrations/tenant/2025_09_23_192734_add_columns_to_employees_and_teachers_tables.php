<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEmployeesAndTeachersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('img_url');
            $table->string('id_card')->nullable()->after('date_of_birth');
            $table->date('date_of_joining')->nullable()->after('id_card');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('image_url');
            $table->string('id_card')->nullable()->after('date_of_birth');
            $table->date('date_of_joining')->nullable()->after('id_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('id_card');
            $table->dropColumn('date_of_joining');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('id_card');
            $table->dropColumn('date_of_joining');
        });
    }
}
