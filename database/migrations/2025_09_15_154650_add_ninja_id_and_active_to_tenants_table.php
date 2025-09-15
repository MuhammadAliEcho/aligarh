<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNinjaIdAndActiveToTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('ninja_id')->nullable()->after('name');
            $table->boolean('active')->default(0)->after('ninja_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['ninja_id', 'active']);
        });
    }
}
