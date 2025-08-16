<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveIdToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('leave_id')->nullable()->after('student_id');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('leave_id')->nullable()->after('teacher_id');
        });

        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('leave_id')->nullable()->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropColumn('leave_id');
        });

        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropColumn('leave_id');
        });

        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropColumn('leave_id');
        });
    }
}
