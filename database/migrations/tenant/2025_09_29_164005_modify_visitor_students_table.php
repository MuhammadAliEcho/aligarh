<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVisitorStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visitor_students', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('seeking_class');
            $table->dropColumn('guardian_relation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visitor_students', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->string('guardian_relation', 256)->after('place_of_birth');
        });
    }
}
